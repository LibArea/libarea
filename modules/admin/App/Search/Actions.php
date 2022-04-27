<?php

namespace Modules\Admin\App\Search;

use Modules\Search\App\Services\Index;

class Actions
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
        $defaultConfig = config('search/config');
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
     * Insert or update a given document to the index
     * @param $document
     * @return bool
     * @throws Exception
     */
    public function update($document)
    {
        return $this->index->update($document);
    }

    /**
     * Insert or update multiple documents to the index
     * @param array $document
     * @return bool
     * @throws Exception
     */
    public function updateMultiple(array $document)
    {
        return $this->index->updateMultiple($document);
    }

    /**
     * delete the given document ID from the index
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function delete($id)
    {
        return $this->index->delete($id);
    }
}
