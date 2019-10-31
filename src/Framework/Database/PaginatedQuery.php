<?php
namespace Framework\Database;

use PDO;
use Framework\Database\Query;
use Framework\Database\QueryResult;
use Pagerfanta\Adapter\AdapterInterface;

/**
 *  adapter pour cette bibilo Pagerfanta
 */
class PaginatedQuery implements AdapterInterface
{
    /*
     * @var Query
     */
    private $query;
    
 
    
    
    public function __construct(Query $query)
    {
        $this->query = $query;
    }


    public function getNbResults(): int
    {
        return $this->query->count();
    }

    /**
     * getSlice
     *
     * @param  mixed $offset
     * @param  mixed $length
     *
     * @return QueryResult
     */
    public function getSlice($offset, $length): QueryResult
    {
        $query = clone $this->query; // probleme mutation du select en OPP
        return $query->limit($length, $offset)->fetchAll();
    }
}
