<?php
namespace Framework\Database;

use PDO;
use Pagerfanta\Adapter\AdapterInterface;

/**
 *  adapter pour cette bibilo Pagerfanta
 */
class PaginatedQuery implements AdapterInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

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
     * @var array
     */
    private $params;
    
    /**
     * __construct
     *
     * @param  \PDO $pdo
     * @param  string $query
     * @param  string $countQuery
     * @param  string $entity|null
     * @param  array $params
     *
     * @return void
     */
    public function __construct(
        \PDO $pdo,
        string $query,
        string $countQuery,
        ?string $entity,
        array $params = []
    ) {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->countQuery = $countQuery;
        $this->entity = $entity;
        $this->params = $params;
    }


    public function getNbResults(): int
    {
        if (!empty($this->params)) {
            $query = $this->pdo->prepare($this->countQuery);
            $query->execute($this->params);
            return $query->fetchColumn();
        }
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
        
        $statement = $this->pdo->prepare($this->query . ' LIMIT :offset, :length');
        foreach ($this->params as $key => $param) {
            $statement->bindParam($key, $param);
        }

        $statement->bindParam('offset', $offset, \PDO::PARAM_INT);
        $statement->bindParam('length', $length, \PDO::PARAM_INT);
        
        if ($this->entity) {
            $statement->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        
        $statement->execute();
        return $statement->fetchAll();
    }
}
