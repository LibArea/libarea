<?php

namespace Modules\Search\App\Query;

class QuerySegment
{
    const Q_NOTHING = '';
    const Q_OR = 'OR';
    const Q_AND = 'AND';
    const Q_NOT = 'NOT';
    const Q_SEARCH = 'SEARCH';

    /** @var string $type */
    public $type;
    /** @var string $field */
    private $field;
    /** @var mixed $value */
    private $value;

    /** @var QuerySegment[] $child */
    private $children = [];

    /**
     * QuerySegment constructor.
     * @param string $type
     */
    public function __construct($type = self::Q_NOTHING)
    {
        $this->type = $type;
    }

    /**
     * @param QuerySegment[] $children
     */
    private function setChildren(array $children)
    {
        $this->children = $children;
    }

    /**
     * @return QuerySegment[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * return a list of every search terms provided into this QuerySegment
     * @return array
     */
    public function getTerms()
    {
        if (empty($this->children)) {
            return [$this->value];
        } else {
            $segment = [];
            foreach ($this->children as $child) {
                $segment = array_merge($segment, $child->getTerms());
            }
            return $segment;
        }
    }

    /**
     * Return the completes hierarchy of this QuerySegment for computing query
     * @return array
     */
    public function getSegment()
    {
        if (empty($this->children)) {
            return [$this->field => [$this->value]];
        } else {
            $segment = [];
            foreach ($this->children as $child) {
                if ($child->type != self::Q_NOTHING) $segment[] = $child;
                else $segment = array_merge_recursive($segment, $child->getSegment());
            }

            return $segment;
        }
    }

    /**
     * Returns true whenever there is a children in the current segment
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    // --- Static functions ----------------------

    /**
     * Search for the exact $term provided in $field
     * @param $field
     * @param $terms
     * @return QuerySegment
     */
    public static function exactSearch($field, $terms)
    {
        $qs = new QuerySegment();
        $qs->field = $field;
        $qs->value = $terms;
        return $qs;
    }

    /**
     * Makes an array of QuerySegments based on exactSearch method
     * @see QuerySegment::exactSearch()
     * @param $field
     * @param $searches
     * @return array
     */
    public static function bulkExactSearch($field, $searches)
    {
        $segs = [];
        foreach ($searches as $search) {
            $segs[] = self::exactSearch($field, $search);
        }
        return $segs;
    }

    /**
     * Regular Search of $term in $field
     * @param $field
     * @param $terms
     * @return QuerySegment
     */
    public static function fieldSearch($field, $terms)
    {
        return self::exactSearch($field . '%', $terms);
    }

    /**
     * Makes an array of QuerySegments based on fieldSearch method
     * @see QuerySegment::fieldSearch()
     * @param $field
     * @param $searches
     * @return array
     */
    public static function bulkFieldSearch($field, $searches)
    {
        $segs = [];
        foreach ($searches as $search) {
            $segs[] = self::fieldSearch($field, $search);
        }
        return $segs;
    }

    /**
     * Search for values in $field where the values is lesser than $terms
     * @param $field
     * @param $terms
     * @return QuerySegment
     */
    public static function lesserSearch($field, $terms)
    {
        return self::exactSearch($field . '<', $terms);
    }
    /**
     * Search for values in $field where the values is lesser or equal to $terms
     * @param $field
     * @param $terms
     * @return QuerySegment
     */
    public static function lesserEqualSearch($field, $terms)
    {
        return self::exactSearch($field . '<=', $terms);
    }
    /**
     * Search for values in $field where the values is greater than $terms
     * @param $field
     * @param $terms
     * @return QuerySegment
     */
    public static function greaterSearch($field, $terms)
    {
        return self::exactSearch($field . '>', $terms);
    }
    /**
     * Search for values in $field where the values is greater or equal to $terms
     * @param $field
     * @param $terms
     * @return QuerySegment
     */
    public static function greaterEqualSearch($field, $terms)
    {
        return self::exactSearch($field . '>=', $terms);
    }
    /**
     * Search for values in $field where the values is not equal to $terms
     * @param $field
     * @param $terms
     * @return QuerySegment
     */
    public static function notEqualSearch($field, $terms)
    {
        return self::exactSearch($field . '!=', $terms);
    }

    /**
     * Merges the regular $simpleQuery with your complete Segment $childSegment
     * @param $simpleQuery
     * @param QuerySegment|null $childSegment
     * @return QuerySegment
     */
    public static function search($simpleQuery, $childSegment)
    {
        $qs = new QuerySegment(self::Q_SEARCH);
        $qs->field = '%';
        $qs->value = $simpleQuery;
        if (!empty($childSegment)) {
            $qs->children = [$childSegment];
        }
        return $qs;
    }

    /**
     * Merges an array of QuerySegments with an AND link
     * @param QuerySegment[] $segments
     * @return QuerySegment
     */
    public static function and(...$segments)
    {
        if (count($segments) == 1 && is_array($segments[0])) {
            $segments = $segments[0];
        }
        if (empty($segments)) return null;
        $qs = new QuerySegment(self::Q_AND);
        $qs->children = $segments;
        return $qs;
    }

    /**
     * Merges an array of QuerySegments with an OR link
     * @param QuerySegment[] $segments
     * @return QuerySegment
     */
    public static function or(...$segments)
    {
        if (count($segments) == 1 && is_array($segments[0])) {
            $segments = $segments[0];
        }
        if (empty($segments)) return null;
        $qs = new QuerySegment(self::Q_OR);
        $qs->children = $segments;
        return $qs;
    }

    /**
     * Negates the provided $segment
     * @param QuerySegment $segment
     * @return QuerySegment
     */
    public static function not(QuerySegment $segment)
    {
        if (substr($segment->field, 0, 1) === '-') {
            $segment->field = substr($segment->field, 1);
        } else {
            $segment->field = '-' . $segment->field;
        }
        return $segment;
    }

    /**
     * Debug your query by creating a human readable string
     * @param QuerySegment $seg
     * @return string
     */
    public static function debug(QuerySegment $seg)
    {
        $rtn = [];
        $prepend = '';
        if (substr($seg->field, 0, 1) === '-') {
            $prepend = 'NOT ';
        }
        foreach ($seg->getSegment() as $field => $value) {
            if ($field == '%') continue;
            if (is_a($value, QuerySegment::class)) {
                $rtn[] = '(' . self::debug($value) . ')';
                continue;
            }
            foreach ($value as $v) {
                if (is_a($v, \DateTime::class)) $v = $v->format(DATE_ATOM);
                $rtn[] = $field . ':"' . $v . '"';
            }
        }
        if ($seg->type === QuerySegment::Q_SEARCH && !empty($seg->value)) {
            $prepend .= 'SEARCH ' . $seg->value;
            if (count($rtn)) {
                $prepend .= ' WITH ';
            }
        }
        if (count($rtn) == 1) {
            return $prepend . current($rtn);
        }
        return $prepend . implode(' ' . $seg->type . ' ', $rtn);
    }
}
