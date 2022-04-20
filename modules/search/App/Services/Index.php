<?php

namespace Modules\Search\App\Services;

use DateTime;
use Exception;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Throwable;
use Modules\Search\App\Query\QuerySegment;
use Modules\Search\App\Services\FAL\Directory;
use Modules\Search\App\Tokenizers\TokenizerInterface;

class Index
{
    /**
     * @var array $config
     */
    private $config;

    /**
     * @var Directory $index
     */
    private $index;

    /**
     * @var Directory $indexDocs
     */
    private $indexDocs = null;

    /**
     * @var Directory $documents
     */
    private $documents;

    /**
     * @var Directory $cache
     */
    private $cache;

    /**
     * @var array $schema
     */
    private $schemas;

    /**
     * @var array $types
     */
    private $types;

    /**
     * @var int $updatingId
     */
    private $updatingId;

    /**
     * @var int $approximateCount
     */
    private $approximateCount;



    /**
     * Index constructor.
     * @param $config
     * @param $schemas
     * @param $types
     * @throws Exception
     */
    public function __construct($config, $schemas, $types)
    {
        $this->config = $config;
        $this->schemas = $schemas;
        $this->types = $types;
        try {
            $this->index = new Directory($config['var_dir'] . $config['index_dir']);
            $this->documents = new Directory($config['var_dir'] . $config['documents_dir'], false);
            $this->cache = new Directory($config['var_dir'] . $config['cache_dir']);
        } catch (Exception $e) {
            throw new Exception('Unable to load Index : ' . $e->getMessage());
        }
    }

    public function getStats()
    {
        return [
            "documentCount" => count($this->documents->scan()),
            "totalTokens" => count(array_keys($this->index->open('all')->getContent())),
            "cacheEntries" => count($this->cache->scan()),
            "schemas" => $this->schemas,
            "types" => $this->types,
            "config" => $this->config
        ];
    }

    /**
     * Create or Update a document into the index
     * @param $document
     * @param bool $clearCache Be aware that when this parameter is false you must free the memory by yourself!
     * @return bool
     * @throws Exception
     */
    public function update($document, $clearCache = true)
    {
        if (is_object($document)) {
            $document = get_object_vars($document);
        }
        if (!isset($document['id'])) {
            throw new Exception("Document should have 'id' property.");
        }
        $this->updatingId = $document['id'];
        if (!isset($document['type'])) {
            throw new Exception("Document should have 'type' property.");
        }
        if (!isset($this->schemas[$document['type']])) {
            throw new Exception("Document type " . $document['type'] . " do not match any of existing types : " . implode(", ", array_keys($this->schemas)));
        }
        if ($this->documents->open($document['id']) !== null) {
            $this->delete($document['id']);
            $this->documents->free();
        }
        if (!$clearCache && $this->indexDocs == null) {
            $this->indexDocs = $this->index->getOrCreateDirectory('docs', true);
        }
        // we should be good now
        $schema = $this->schemas[$document['type']];
        // building document
        list($doc, $index) = $this->buildDoc($document, $schema);
        $tmp = new RecursiveIteratorIterator(new RecursiveArrayIterator($index));
        $index = [];
        foreach ($tmp as $k => $v) {
            if (isset($index[$k])) {
                $index[$k] += !empty($v) ? $v : 0;
            } else {
                $index[$k] = !empty($v) ? $v : 0;
            }
        }
        $this->updateIndex($index, $document['id']);
        $this->updateDocument($doc, $document['id']);
        if ($clearCache) {
            $this->clearCache();
        }
        return true;
    }


    /**
     * Memory optimized indexation of multiple documents
     * @param array $documents
     * @return bool
     * @throws Exception
     */
    public function updateMultiple(array &$documents)
    {
        $count = 0;
        foreach ($documents as &$document) {
            $this->update($document, false);
            $document = null;
            $count++;
            if ($count % 50 == 0) {
                $this->freeMemory();
            }
        }
        $this->freeMemory();
        $this->clearCache();
        return true;
    }

    /**
     * Deletes the provided $id from the index
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function delete($id)
    {
        // remove document
        $this->documents->delete($id);
        if ($this->indexDocs == null) {
            $this->indexDocs = $this->index->getOrCreateDirectory('docs', true);
        }
        $this->indexDocs->delete($id);
        // clear the index of every references
        $allFiles = $this->index->openAll();
        $allTokensFile = $this->index->open('all');
        $allTokens = $allTokensFile->getContent();
        if ($allFiles) {
            foreach ($allFiles as $file) {
                if ($file->getName() == 'all') continue;
                $tokens = $file->getContent();
                $tokensToRemove = [];
                foreach ($tokens as $tokenName => &$token) {
                    if (isset($token[$id])) {
                        try {
                            unset($token[$id]);
                        } catch (Throwable $ex) {
                        }
                        if (empty($token)) {
                            $tokensToRemove[] = $tokenName;
                        }
                    }
                }
                foreach ($tokensToRemove as $tokenName) {
                    if (isset($tokens[$tokenName]))
                        unset($tokens[$tokenName]);
                    if (isset($allTokens[$tokenName]))
                        unset($allTokens[$tokenName]);
                }
                if (empty($tokens)) {
                    $file->delete();
                } else {
                    $file->setContent($tokens);
                }
            }
        }
        $allTokensFile->setContent($allTokens);
        $this->clearCache();
        return true;
    }

    /**
     * WARNING If you use this function be sure to know what you are doing !
     * Backup your document folder before executing this !
     * You can lose your documents or data if you stop the operation prematurely
     * Be sure that your max execution time ini parameter is big enough to handle.
     * Main reason to use :
     * - refresh fields after updating the engine so that you can use the new index feature
     * @return array : errors encountered while rebuilding
     * @throws Exception
     */
    public function rebuild()
    {
        $documents = $this->documents->openAll();
        $errors = [];
        foreach ($documents as $document) {
            try {
                $this->update($document->getContent());
            } catch (Exception $ex) {
                $errors[] = "file '" . $document->getName() . "' : " . $ex->getMessage();
            }
        }
        $this->clearCache();
        return $errors;
    }

    /**
     * Clears the cache directory
     * @throws Exception
     */
    public function clearCache()
    {
        $this->cache->deleteAll(false);
    }

    /**
     * Performs a search
     * @param $query
     * @param array $filters
     * @return array
     * @throws Exception
     */
    public function search($query, $filters = [])
    {
        $tokens = [];
        if (!isset($filters['offset'])) $filters['offset'] = 0;
        if (is_string($query)) {
            // simple search
            $tokens = $this->tokenizeQuery($query);

            asort($tokens);
            $tmp = array_merge($tokens, $filters);
            arsort($tmp);
            $md5 = md5(serialize($tmp));
            $cached = $this->getCache($md5);
            if (!empty($cached)) {
                return $cached;
            }

            $results = [];
            if (!empty($tokens)) {
                // TODO: extract this block #1
                foreach ($tokens as $token) {
                    $this->approximateCount = 0;
                    $this->computeScore($results, $this->find($token));
                }
            } else {
                // TODO: extract this block #2
                $results = array_flip($this->documents->scan());
                foreach ($results as $key => &$value) {
                    $value = 0;
                }
            }
        } else {
            // precise search
            $results = [];

            asort($filters);
            $tmp = [
                "query" => $query,
                "filters" => $filters
            ];
            $md5 = "precise_" . md5(serialize($tmp));
            $cached = $this->getCache($md5);
            if (!empty($cached)) {
                return $cached;
            }
            $regularResult = [];
            if (is_a($query, QuerySegment::class)) {
                $regularQuery = "";
                /** @var QuerySegment $query */
                if ($query->type == QuerySegment::Q_SEARCH) {
                    $regularQuery = $query->getValue();
                    $tokens = $this->tokenizeQuery($query->getValue());
                    if (empty($query->getValue()) && !$query->hasChildren()) {
                        // TODO: extract this block #2
                        $regularResult = array_flip($this->documents->scan());
                        foreach ($regularResult as $key => &$value) {
                            $value = 0;
                        }
                    } else if (!empty($tokens)) {
                        // TODO: extract this block #1
                        foreach ($tokens as $token) {
                            $this->approximateCount = 0;
                            $this->computeScore($regularResult, $this->find($token));
                        }
                    }
                    $query = $query->getChildren()[0] ?? null;
                }
                if (!empty($query)) {
                    $results = $this->depileSegment($query);
                    if ($filters['connex']) {
                        foreach ($query->getTerms() as $term) {
                            $tokens = array_merge($tokens, $this->tokenizeQuery($term));
                        }
                    }
                } else {
                    $results = $regularResult;
                }
                $query = $regularQuery;
            } else {
                throw new Exception('Old QueryBuilder queries are not supported anymore. Please use QuerySegment class instead.');
            }
            if (!empty($regularResult)) {
                $results = array_intersect_key($regularResult, $results);
            }
        }
        arsort($results);
        $facets = $this->processFacets($results, $query, $filters);
        $documents = $this->processResults($results, $filters);
        $response = [
            'numFound' => count($results),
            'maxScore' => !empty($results) ? max($results) : 0,
            'documents' => $documents,
            'facets' => $facets,
            'connex' => []
        ];
        if ($filters['connex'] ?? false) {
            $response['connex'] = $this->processConnex($results, $tokens) ?? [];
        }
        $this->setCache($md5, $response);
        return $response;
    }

    /**
     * Walk through QuerySegment $qs and perform a search based of the segments
     * @param QuerySegment $qs
     * @return array|string
     * @throws Exception
     */
    private function depileSegment(QuerySegment $qs)
    {
        $results = [];
        $first = true;
        foreach ($qs->getSegment() as $field => $value) {
            list($not, $mode, $trueField) = $this->describeField($field);
            if (is_a($value, QuerySegment::class)) {
                $currentResult = $this->depileSegment($value);
            } else {
                $currentResult = [];
                $subFirst = true;
                foreach ($value as $v) {
                    $subResult = $this->getAdvancedResult($mode, $trueField, $v);
                    $this->mergeSegments($qs, $currentResult, $subFirst, $not, $subResult);
                    $subFirst = false;
                }
            }
            $this->mergeSegments($qs, $results, $first, $not, $currentResult);
            $first = false;
        }
        return $results;
    }

    /**
     * Merges results based of the boolean operator AND/OR of a QuerySegment $qs, with the $not and $first parameters
     * @param QuerySegment $qs
     * @param $results
     * @param bool $first
     * @param $not
     * @param $currentResult
     */
    private function mergeSegments(QuerySegment $qs, &$results, bool $first, $not, $currentResult)
    {
        if ($not) {
            if ($first) {
                $results = array_flip($this->documents->scan());
                foreach ($results as $k => &$v) {
                    $v = 0;
                }
            }
            $currentResult = array_diff_key($results, $currentResult);
        }
        if ($qs->type === QuerySegment::Q_OR) {
            $this->computeScore($results, $currentResult);
        } elseif ($qs->type === QuerySegment::Q_AND) {
            if ($first) {
                $results = $currentResult;
            } else {
                $results = array_intersect_key($results, $currentResult);
            }
        }
    }

    /**
     * Extract search parameters for the provided $field
     * @param $field
     * @return array [not, mode, trueFieldName]
     */
    private function describeField($field): array
    {
        $not = false;
        $mode = '';
        $trueField = $field;
        if (substr($field, 0, 1) === '-') {
            $not = true;
            $trueField = substr($field, 1);
        }
        if (in_array(substr($trueField, -1), ['<', '>', '='])) {
            $mode = substr($trueField, -1);
            $trueField = substr($trueField, 0, -1);
            if (in_array(substr($trueField, -1), ['<', '>', '!'])) {
                $mode = substr($trueField, -1) . $mode;
                $trueField = substr($trueField, 0, -1);
            }
        }
        if (substr($field, -1) == "%") {
            $mode = '%';
            $trueField = substr($trueField, 0, -1);
        }
        return array($not, $mode, $trueField);
    }

    /**
     * Processes query with the provided $mode
     * @param string $mode '' / '%' / '<' / '<=' / '>' / '>=' / '!='
     * @param $field
     * @param $value
     * @param array $fieldResults
     * @return array
     * @throws Exception
     */
    private function getAdvancedResult(string $mode, $field, $value, $fieldResults = []): array
    {
        if (is_object($value) && isset($this->config['serializableObjects'][get_class($value)])) $value = $this->config['serializableObjects'][get_class($value)]($value);
        switch ($mode) {
            case '%': // process regular query
                if ($this->index->open('values_' . $field, false) !== null) {
                    $array = $this->index->open('values_' . $field, false)->getContent();
                    $tokens = $this->tokenizeQuery($value);
                    foreach ($tokens as $token) {
                        foreach ($array as $indexValue => $indexDocuments) {
                            if (strpos($indexValue, strval($token)) !== false) {
                                $this->computeScore($fieldResults, $indexDocuments);
                            }
                        }
                    }
                }
                break;
            case '<': // process "Lesser than" query
                if ($this->index->open('exact_' . $field, false) !== null) {
                    $array = $this->index->open('exact_' . $field, false)->getContent();
                    ksort($array);
                    foreach ($array as $k => $v) {
                        if ($k >= $value) break;
                        $this->computeScore($fieldResults, $array[$k] ?? []);
                    }
                }
                break;
            case '>': // process "Greater than" query
                if ($this->index->open('exact_' . $field, false) !== null) {
                    $array = $this->index->open('exact_' . $field, false)->getContent();
                    ksort($array);
                    $found = false;
                    foreach ($array as $k => $v) {
                        if ($k >= $value) $found = true;
                        if (!$found) continue;
                        if ($k != $value) $this->computeScore($fieldResults, $array[$k] ?? []);
                    }
                }
                break;
            case '<=': // process "Lesser than or Equal" query
                if ($this->index->open('exact_' . $field, false) !== null) {
                    $array = $this->index->open('exact_' . $field, false)->getContent();
                    ksort($array);
                    foreach ($array as $k => $v) {
                        if ($k > $value) break;
                        $this->computeScore($fieldResults, $array[$k] ?? []);
                    }
                }
                break;
            case '>=': // process "Greater than or Equal" query
                if ($this->index->open('exact_' . $field, false) !== null) {
                    $array = $this->index->open('exact_' . $field, false)->getContent();
                    ksort($array);
                    $found = false;
                    foreach ($array as $k => $v) {
                        if ($k >= $value) $found = true;
                        if (!$found) continue;
                        $this->computeScore($fieldResults, $array[$k] ?? []);
                    }
                }
                break;
            case '!=':
                if ($this->index->open('exact_' . $field, false) !== null) {
                    $array = $this->index->open('exact_' . $field, false)->getContent();
                    foreach ($array as $k => $v) {
                        if ($k == $value) continue;
                        $this->computeScore($fieldResults, $array[$k] ?? []);
                    }
                }
                break;
            default: // process exact search
                if ($this->index->open('exact_' . $field, false) !== null) {
                    $array = $this->index->open('exact_' . $field, false)->getContent();
                    $this->computeScore($fieldResults, $array[$value] ?? []);
                }
        }
        return $fieldResults;
    }

    /**
     * Get the content of the document with $id
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function getDocument($id)
    {
        $file = $this->documents->open($id);
        return $file->getContent();
    }

    /**
     * Returns the configured schema
     * @return array
     */
    public function getSchemas()
    {
        return $this->schemas;
    }

    /**
     * Sets the schema
     * @param array $schemas
     */
    public function setSchemas($schemas)
    {
        $this->schemas = $schemas;
    }

    /**
     * Returns the configured types
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Sets the types
     * @param array $types
     */
    public function setTypes($types)
    {
        $this->types = $types;
    }


    /**
     * Closes every opened files, freeing memory
     */
    public function freeMemory()
    {
        $this->index->free();
        $this->documents->free();
        $this->cache->free();
        if ($this->indexDocs != null) $this->indexDocs->free();
    }

    /**
     * Find documents that match the provided $token
     * Try to approximate the token if nothing is found
     * @param $token
     * @return array
     * @throws Exception
     */
    private function find($token)
    {
        if (empty($token)) return [];
        $file = $this->index->open(base64_encode(substr($token, 0, 1)));
        $index = $file->getContent();
        if (!isset($index[$token])) {
            // find approximative tokens
            return $this->fuzzyFind($token);
        }

        return $index[$token];
    }

    /**
     * @param $token
     * @param bool $providePonderations
     * @return array
     * @throws Exception
     * @deprecated Suggesting functions now have another suggestion function available. Please use suggestToken($token, $providePonderations) instead
     */
    public function suggest($token, $providePonderations = false)
    {
        return $this->suggestToken($token, $providePonderations);
    }

    /**
     * @param $field
     * @param $value
     * @param bool|string $wrapSpan if true, wrap <span> tags around the matching values.
     *                              if it's a string, adds the string as a class
     * @return array
     * @throws Exception
     */
    public function suggestField($field, $value, $wrapSpan = false)
    {
        $cached = $this->getCache('suggest_' . md5($field . '_' . $value . '_' . $wrapSpan));
        if (!empty($cached)) {
            return $cached;
        }
        $exactFile = $this->index->open('exact_' . $field);
        if ($exactFile !== null) {
            $value = strtolower($value);
            $exactContent = array_keys($exactFile->getContent());
            $matching = [];
            foreach ($exactContent as $exactValue) {
                $exactValue = strtolower($exactValue); // maybe extract this to configuration ? (tokenizers)
                $strPos = strpos($exactValue, strval($value));
                if ($strPos !== false) {
                    if ($wrapSpan !== false) {
                        $span = '<span';
                        if (is_string($wrapSpan)) {
                            $span .= ' class="' . $wrapSpan . '"';
                        }
                        $span .= '>';
                        $exactValue = str_replace($value, $span . $value . '</span>', $exactValue);
                    }
                    $matching[$exactValue] = $strPos;
                }
            }
            asort($matching);
            $matching = array_keys($matching);
            $this->setCache('suggest_' . md5($field . '_' . $value . '_' . $wrapSpan), $matching);
            return $matching;
        }
        return [];
    }

    /**
     * @param $token
     * @param bool $providePonderations
     * @return array
     * @throws Exception
     */
    public function suggestToken($token, $providePonderations = false)
    {
        if (empty($token)) return [];
        $cached = $this->getCache('suggestToken_' . md5($token . '_' . $providePonderations));
        if (!empty($cached)) {
            return $cached;
        }
        $all = $this->index->open('all');
        $tokens = array_keys($all->getContent());
        $matching = [];
        foreach ($tokens as $indexToken) {
            $strPos = strpos($indexToken, strval($token));
            if ($strPos !== false) {
                $matching[$indexToken] = $strPos;
            }
        }
        asort($matching);
        if (!$providePonderations) {
            $matching = array_keys($matching);
        }
        $this->setCache('suggestToken_' . md5($token . '_' . $providePonderations), $matching);
        return $matching;
    }

    /**
     * Try to find a token based of the provided $token
     * Will search for misstypes using approximate function
     * @param $token
     * @return array
     * @throws Exception
     */
    private function fuzzyFind($token)
    {
        if (empty($token) || $this->config['fuzzy_cost'] == 0) return [];
        $matching = $this->suggestToken($token, true);
        if (empty($matching)) {
            if ($this->config['approximate_limit'] < 0 || $this->approximateCount < $this->config['approximate_limit']) {
                // approximate_limit is here for preventing the usage of this CPU intensive function
                $matching = $this->approximate($token, $this->config['fuzzy_cost']);
                $this->approximateCount++;
            }
        }
        $found = [];
        if (!empty($matching)) {
            reset($matching);
            $minPonderation = current($matching);
            foreach ($matching as $match => $ponderation) {
                if ($ponderation == $minPonderation) {
                    $found = array_replace($found, $this->find($match));
                }
            }
        } else {
            $found = $this->find(substr($token, 0, -1));
        }

        return $found;
    }

    /**
     * Search for misstypes in the provided $term, with a limit $cost
     * @param $term
     * @param $cost
     * @param array $positions
     * @return array|mixed
     * @throws Exception
     */
    private function approximate($term, $cost, $positions = [])
    {
        $cached = $this->getCache('approx_' . base64_encode($term));
        if (!empty($cached)) {
            return $cached;
        }
        $termL = strlen($term);
        if ($termL <= 1) return []; // we shouldn't approximate one character
        if ($cost > $termL - 1) $cost = $termL - 1; // The cost can't be more than the term's length itself
        $tokens = array_keys($this->index->open('all')->getContent());
        $matching = [];
        for ($i = 0; $i < $termL; $i++) {
            $termToFind = substr_replace($term, '', $i, 1);
            foreach ($tokens as $token) {
                $originalToken = $token;
                if (!empty($positions)) {
                    foreach ($positions as $position) {
                        $token = substr_replace($token, '', $position, 1);
                    }
                }
                if (strlen($token) >= $termL) {
                    $tokenToLink = substr_replace($token, '', $i, 1);
                    $strPos = strpos($tokenToLink, strval($termToFind));
                    if ($strPos !== false) {
                        $matching[$originalToken] = $strPos;
                    }
                }
            }
            if ($cost > 1) {
                $positions[$cost] = $i;
                $matching = array_replace($matching, $this->approximate($termToFind, $cost - 1, $positions));
            }
        }
        asort($matching);
        $this->setCache('approx_' . $term, $matching);
        return $matching;
    }

    /**
     * Builds the document's index and fields
     * @param $data
     * @param $schema
     * @return array
     * @throws Exception
     */
    private function buildDoc($data, $schema)
    {
        $doc = [];
        if (isset($data['id'])) $doc['id'] = $data['id'];
        if (isset($data['type'])) $doc['type'] = $data['type'];
        $index = [];
        foreach ($schema as $field => $definition) {
            $doc[$field] = $this->buildField($field, $definition, $data);
            $index[$field] = $this->buildIndex($field, $definition, $data);
        }
        return [$doc, $index];
    }

    /**
     * Build field for storing into the document
     * @param $fieldName
     * @param $definition
     * @param $data
     * @return array|DateTime|mixed
     * @throws Exception
     */
    private function buildField($fieldName, $definition, $data)
    {
        switch ($definition['_type']) {
            case 'datetime':
                if (is_a((!empty($fieldName) ? $data[$fieldName] : $data), DateTime::class)) {
                    return (!empty($fieldName) ? $data[$fieldName] : $data);
                }
                return new DateTime(!empty($fieldName) ? $data[$fieldName] : $data);
                break;
            case 'list':
                $def = array_merge($definition, ['_type' => $definition['_type.']]);
                $tmp = [];
                if (!empty($fieldName) ? !empty($data[$fieldName]) : !empty($data)) {
                    foreach (!empty($fieldName) ? $data[$fieldName] : $data as $d) {
                        $tmp[] = $this->buildField('', $def, $d);
                    }
                }
                return $tmp;
                break;
            case 'array':
                return $this->buildDoc(!empty($fieldName) ? $data[$fieldName] : $data, $definition['_array'])[0];
                break;
            default:
                return !empty($fieldName) ? $data[$fieldName] : $data;
                break;
        }
    }

    /**
     * Builds the index and tokenize every authorized terms
     * @param $fieldName
     * @param $definition
     * @param $data
     * @return array|mixed|null|RecursiveIteratorIterator|string
     * @throws Exception
     */
    private function buildIndex($fieldName, $definition, $data)
    {
        if (empty($definition['_name'])) $definition['_name'] = $fieldName;
        if (!isset($definition['_indexed'])) $definition['_indexed'] = false;
        switch ($definition['_type']) {
            case 'datetime':
                $this->buildFilter(!empty($fieldName) ? $data[$fieldName] : $data, $definition);
                if (is_a((!empty($fieldName) ? $data[$fieldName] : $data), DateTime::class)) {
                    $dt = (!empty($fieldName) ? $data[$fieldName] : $data);
                } else {
                    $dt = new DateTime(!empty($fieldName) ? $data[$fieldName] : $data);
                }
                return $definition['_indexed'] ? $this->tokenize($dt, $definition) : "";
                break;
            case 'list':
                $def = array_merge($definition, ['_type' => $definition['_type.']]);
                $tmp = [];
                if (!empty($fieldName) ? !empty($data[$fieldName]) : !empty($data)) {
                    foreach (!empty($fieldName) ? $data[$fieldName] : $data as $d) {
                        $tmp[] = $this->buildIndex('', $def, $d);
                    }
                }
                return $tmp;
                break;
            case 'array':
                return $this->buildDoc(!empty($fieldName) ? $data[$fieldName] : $data, $definition['_array'])[1];
                break;
            default:
                $this->buildFilter(!empty($fieldName) ? $data[$fieldName] : $data, $definition);
                return $definition['_indexed'] ? $this->tokenize(!empty($fieldName) ? $data[$fieldName] : $data, $definition) : '';
                break;
        }
    }

    /**
     * Tokenizes a query string
     * @param $query
     * @param string $type
     * @return array
     */
    public function tokenizeQuery($query, $type = 'search')
    {
        return array_keys($this->tokenize($query, ['_type' => $type, '_boost' => 0]));
    }

    /**
     * Tokenize a field based on his $def
     * @param $data
     * @param $def
     * @return array|null|RecursiveIteratorIterator
     */
    private function tokenize($data, $def)
    {
        /** @var TokenizerInterface[] $typeDef */
        $typeDef = isset($this->types[$def['_type']]) ? $this->types[$def['_type']] : $this->types['_default'];
        if (!isset($def['_boost'])) $def['_boost'] = 1;

        if (!is_array($data)) {
            $data = [$data];
        }
        foreach ($typeDef as $tokenizer) {
            $tmp = $tokenizer::tokenize($data);
            $data = [];
            array_walk_recursive($tmp, function ($e) use (&$data) {
                $data[] = $e;
            });
        }
        $data = array_filter($data);
        $res = [];
        foreach ($data as $d => $k) {
            if (isset($res[$k])) {
                $res[$k] += $def['_boost'];
            } else {
                $res[$k] = $def['_boost'];
            }
        }
        return $res;
    }

    /**
     * Generates facets and writes them into the index directory
     * @param $data
     * @param $def
     * @return void
     * @throws Exception
     */
    private function buildFilter($data, $def)
    {
        $filterable = isset($def['_filterable']) ? $def['_filterable'] : false;
        if ($filterable) {
            $file = $this->index->open('facet_' . $def['_name']);
            $array = [];
            $array[$data][$this->updatingId] = $this->updatingId;
            $file->addContent($array);
        }
        $file = $this->index->open("values_" . $def['_name']);
        $exact = $this->index->open("exact_" . $def['_name']);
        $array = [];
        if (!is_array($array)) $array = [];
        if (is_object($data)) {
            if (isset($this->config['serializableObjects'][get_class($data)])) {
                $data = $this->config['serializableObjects'][get_class($data)]($data);
            } else {
                throw new Exception("Field " . $def['_name'] . " of document ID " . $this->updatingId . " is an object of type " . get_class($data) . " that is not supported by the currently configured SerializableObjects.");
            }
        }
        $array[$data][$this->updatingId] = $def['_boost'] ?? 1;
        if (!empty($array)) {
            $exact->addContent($array);
        }
        $array = [];
        if (!is_array($array)) $array = [];
        $tokens = $this->tokenize($data, $def);
        foreach ($tokens as $token => $score) {
            $array[$token][$this->updatingId] = $score;
        }
        if (!empty($array)) {
            $file->addContent($array);
        }
    }

    /**
     * Writes a document into the documents directory
     * @param $doc
     * @param $id
     * @throws Exception
     */
    private function updateDocument($doc, $id)
    {
        $file = $this->documents->open($id);
        $file->setContent($doc);
    }

    /**
     * Writes index data into the index directory
     * @param $index
     * @param $id
     * @throws Exception
     */
    private function updateIndex($index, $id)
    {
        $file = $this->index->open('all');
        if ($this->indexDocs == null) {
            $this->indexDocs = $this->index->getOrCreateDirectory("docs");
        }
        $document = $this->indexDocs->open($id);
        $document->setContent($index);
        $all = [];
        foreach ($index as $token => $score) {
            $t = base64_encode(substr($token, 0, 1));
            if (!isset($all[$token])) {
                $all[$token] = $t;
            }
            $f = $this->index->open($t);
            $tokens = [];
            if (!is_array($tokens)) $tokens = [];
            if (!isset($tokens[$token])) {
                $tokens[$token] = ["$id" => $score];
            } else {
                $tokens[$token]["$id"] = $score;
            }
            $f->addContent($tokens);
        }
        $file->addContent($all);
    }

    /**
     * Sets a cache entry
     * @param $identifier
     * @param $response
     * @throws Exception
     */
    private function setCache($identifier, $response)
    {
        $file = $this->cache->open($identifier);
        $file->setContent($response);
    }

    /**
     * Get a cache entry
     * @param $identifier
     * @return mixed
     * @throws Exception
     */
    private function getCache($identifier)
    {
        $file = $this->cache->open($identifier);
        return $file->getContent();
    }

    /**
     * Compiles results using the current filters. This is the last step of the search
     * uses limit, offset and order
     * @param $filters
     * @param array $results
     * @return array
     * @throws Exception
     */
    private function processResults(array $results, $filters): array
    {
        if (isset($filters['order']) && !empty($filters['order'])) {
            $nonOrdered = $results;
            $results = [];
            foreach ($filters['order'] as $field => $direction) {
                if ($this->index->open('exact_' . $field, false) !== null) {
                    $array = $this->index->open('exact_' . $field, false)->getContent();
                    if ($direction === 'ASC') {
                        ksort($array);
                    } elseif ($direction === 'DESC') {
                        krsort($array);
                    }
                    foreach ($array as $key => $ids) {
                        foreach ($ids as $id => $falseScore) {
                            if (in_array($id, array_keys($nonOrdered))) {
                                $results[$id] = $nonOrdered[$id];
                            }
                        }
                    }
                }
            }
        }
        $documents = [];
        $i = 0;
        if (!isset($filters['offset'])) $filters['offset'] = 0;
        foreach ($results as $doc => $score) {
            if ($i < $filters['offset']) {
                $i++;
                continue;
            }
            if (isset($filters['limit']) && $i >= $filters['offset'] + $filters['limit']) break;
            $documents[$doc] = $this->documents->open($doc)->getContent();
            $documents[$doc]['_score'] = $score;
            $i++;
        }
        return $documents;
    }

    /**
     * Compiles the facets asked based of the results.
     * @param array $results
     * @param $query
     * @param $filters
     * @return array
     * @throws Exception
     */
    private function processFacets(array $results, $query, $filters): array
    {
        $facets = [];
        if (isset($filters['facets']) && !empty($filters['facets'])) {
            if (!empty($query)) {
                foreach ($filters['facets'] as $facet) {
                    if ($this->index->open('facet_' . $facet, false) !== null) {
                        $array = $this->index->open('facet_' . $facet, false)->getContent();
                        foreach ($array as $token => $ids) {
                            $facets[$facet][$token] = count(array_intersect_key(array_flip($ids), $results));
                        }
                        arsort($facets[$facet]);
                    }
                }
            } else {
                foreach ($filters['facets'] as $facet) {
                    if ($this->index->open('facet_' . $facet, false) !== null) {
                        $array = $this->index->open('facet_' . $facet, false)->getContent();
                        foreach ($array as $name => $ids) {
                            $facets[$facet][$name] = count($ids);
                        }
                        arsort($facets[$facet]);
                    }
                }
            }
        }
        return $facets;
    }

    /**
     * Merges results by adding scores from $scoreArray per token
     * @param array $results
     * @param array $scoreArray
     */
    private function computeScore(array &$results, array $scoreArray)
    {
        foreach ($scoreArray as $k => $v) {
            if (!isset($results[$k])) {
                $results[$k] = $v;
            } else {
                $results[$k] += $v;
            }
        }
    }

    /**
     * Try to find related documents based on common token of the top documents found
     * @param array $documents
     * @param array $searchTokens
     * @return array
     * @throws Exception
     */
    private function processConnex(array $documents, array $searchTokens)
    {
        if (!empty($documents)) {
            $backup_fuzzy_cost = $this->config['fuzzy_cost'];
            $this->config['fuzzy_cost'] = 0; // disable fuzzy search because of high computation generated by it
            reset($documents);
            if ($this->indexDocs == null) {
                $this->indexDocs = $this->index->getOrCreateDirectory("docs");
            }
            $maxScore = current($documents);
            if ($maxScore == 0) $maxScore = 1; // prevent division by zero
            $count = 0;
            $tokens = [];
            $accuracy = [];
            foreach ($documents as $id => $docScore) {
                $scorePercentage = ($docScore / $maxScore);
                if ($scorePercentage > $this->config['connex']['threshold'] || $count < $this->config['connex']['min']) {
                    $docTokens = $this->indexDocs->open($id)->getContent();
                    $this->computeScore($tokens, $docTokens);
                    foreach ($docTokens as $token => $score) {
                        if (!isset($accuracy[$token])) $accuracy[$token] = [];
                        $accuracy[$token][] = $scorePercentage;
                    }
                    $count++;
                    // no need to read the entire index if everything is matching the threshold
                    if ($count >= $this->config['connex']['max']) break;
                } else {
                    break; // no more entries that match the score
                }
            }
            $tokens = array_diff_key($tokens, array_flip($searchTokens));
            $count = 0;
            $result = [];
            $connexDocs = [];
            foreach ($tokens as $token => $score) {
                $found = $this->find($token);
                foreach ($accuracy[$token] as $accu) {
                    if (!isset($result[$token])) $result[$token] = 0;
                    $result[$token] += $score * $accu;
                }
                foreach ($found as &$foundScore) {
                    $foundScore += $result[$token];
                }
                $this->computeScore($connexDocs, $found);
                $count++;
                if ($count >= $this->config['connex']['limitToken']) break; // limit the number of tokens returned
            }
            arsort($result);
            $connexDocs = array_diff_key($connexDocs, $documents);
            arsort($connexDocs);
            $this->config['fuzzy_cost'] = $backup_fuzzy_cost;
            return [
                'tokens' => $result,
                'documents' => $this->processResults($connexDocs, ['limit' => $this->config['connex']['limitDocs']])
            ];
        }
    }
}
