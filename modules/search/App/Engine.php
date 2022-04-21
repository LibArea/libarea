<?php

namespace Modules\Search\App;

use Modules\Search\App\Query\QueryBuilder;
use Modules\Search\App\Services\Index;
use Config;

class Engine
{
    /**
     * @var Index $index
     */
    private $index;

    /**
     * @var array $config
     */
    private $config;

    /**
     * Engine constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct($config = [])
    {
        $defaultConfig = Config::get('search/config');
        $this->config = array_replace_recursive($defaultConfig, $config);
        $this->index = new Index($this->config['config'], $this->config['schemas'], $this->config['types']);
    }

    /**
     * Get the Engine's index. Used to perform modifications to the index,
     * such as clearing the cache or rebuilding the index
     * @return Index
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * perform a search
     * @param string|array|QueryBuilder $query
     * @param array $filters
     * @return array
     * @throws Exception
     */
    public function search($query, $filters = [])
    {
        if (is_a($query, QueryBuilder::class)) {
            return $this->index->search($query->getQuery(), $query->getFilters());
        }
        return $this->index->search($query, $filters);
    }

    /**
     * @param $token
     * @param bool $providePonderations
     * @return array
     * @throws Exception
     * @deprecated Suggesting functions now have another suggestion function available. Please use suggestToken($token) instead
     */
    public function suggest($token)
    {
        return $this->suggestToken($token);
    }

    /**
     * Suggest last word for a search
     * @param $query
     * @return array
     * @throws Exception
     */
    public function suggestToken($query)
    {
        $terms = explode(' ', $query);
        $search = array_pop($terms);
        $tokens = $this->index->tokenizeQuery($search);
        $suggestions = [];
        foreach ($tokens as $token) {
            $suggestions = array_replace($suggestions, $this->index->suggestToken($token));
        }
        $before = implode(' ', $terms);
        foreach ($suggestions as &$suggest) {
            $suggest = $before . ' ' . $suggest;
        }
        return array_chunk($suggestions, 10)[0];
    }

    /**
     * @param $field
     * @param $value
     * @param bool|string $wrapSpan if true, wrap <span> tags around the matching values.
     *                              if it's a string, adds the string as a class
     * @return array
     * @throws Exception -  ô'||'îðóì  
     */
    public function suggestField($field, $value, $wrapSpan = false)
    {
        return $this->index->suggestField($field, $value, $wrapSpan);
    }

}
