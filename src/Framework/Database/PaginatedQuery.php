<?php
namespace Framework\Database;

use Pagerfanta\Adapter\AdapterInterface;

/**
 *  adapter pour cette bibilo Pagerfanta
 */
class PaginatedQuery implements AdapterInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

     /**
     * @var string
     */
    private $query;
     /**
     * @var string
     */
    private $countQuery;

      /**
     * @var string
     */
    private $entity;
    
    
    /**
     * __construct
     *
     * @param  mixed $pdo
     * @param  mixed $query
     * @param  mixed $countQuery
     * @param  mixed $entity|null
     *
     * @return void
     */
    public function __construct(\PDO $pdo, string $query, string $countQuery, ?string $entity)
    {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->countQuery = $countQuery;
        $this->entity = $entity;
    }


    public function getNbResults(): int
    {
        return $this->pdo->query($this->countQuery)->fetchColumn();
    }

    /**
     * getSlice
     *
     * @param  mixed $offset
     * @param  mixed $length
     *
     * @return array de Entity
     */
    public function getSlice($offset, $length): array
    {
        $offset = (int)$offset;
        $length =(int) $length;

        $statement = $this->pdo->prepare($this->query . ' LIMIT :offset, :length');
        $statement->bindParam('offset', $offset, \PDO::PARAM_INT);
        $statement->bindParam('length', $length, \PDO::PARAM_INT);
        
        if ($this->entity) {
            $statement->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        
        $statement->execute();
        return $statement->fetchAll();
    }
}
