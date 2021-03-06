<?php

/**
 * Class MP_Debug_Model_Query
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 */
class MP_Debug_Model_Query
{
    protected $queryType;
    protected $query;
    protected $queryParams;
    protected $elapsedSecs;
    protected $stacktrace;


    /**
     * MP_Debug_Model_Query constructor.
     *
     * @param Zend_Db_Profiler_Query $profilerQuery
     * @param string                 $stacktrace
     */
    public function init(Zend_Db_Profiler_Query $profilerQuery, $stacktrace = '')
    {
        $this->queryType = $profilerQuery->getQueryType();
        $this->query = $profilerQuery->getQuery();
        $this->queryParams = $profilerQuery->getQueryParams();
        $this->elapsedSecs = $profilerQuery->getElapsedSecs();
        $this->stacktrace = $stacktrace;
    }


    /**
     * Returns query type
     *
     * @return int
     */
    public function getQueryType()
    {
        return $this->queryType;
    }


    /**
     * Returns SQL query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }


    /**
     * Returns SQL query parameters
     *
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }


    /**
     * Returns execution time in seconds
     *
     * @return false|float
     */
    public function getElapsedSecs()
    {
        return $this->elapsedSecs;
    }

    /**
     * @return string
     */
    public function getStacktrace()
    {
        return $this->stacktrace;
    }

}
