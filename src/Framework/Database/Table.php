<?php

namespace Framework\Database;

use Pagerfanta\Pagerfanta;

class Table
{

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * Nom de la table en BDD
     * @var string
     */
    protected $table;
     /**
     * Nom de l'entité utilisé
     * @var string|null
     */
    protected $entity;




    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Pagine des elements avec pagerfanta/pagerfanta
     *
     * @return Pagerfanta
     */
    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            $this->paginationQuery(),
            "SELECT COUNT(id) FROM {$this->table}",
            $this->entity
        );

        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
        /* return $this->pdo
            ->query('SELECT * FROM posts ORDER BY created_at DESC LIMIT 10')
            ->fetchAll(); */
    }

    /**
     * paginationQuery base Query peut etre ecrase par enfants
     *
     * @return string
     */
    protected function paginationQuery()
    {
        return  "SELECT * FROM {$this->table}" ;
    }


    public function findList(): array
    {
        $results = $this->pdo
            ->query("SELECT id, name FROM  {$this->table} ")
            ->fetchAll(\PDO::FETCH_NUM);
        $list = [];
        foreach ($results as $result) {
            $list[ $result[0]] = $result[1];
        }
        return $list;
    }

     /**
     * Récupère un élément à partir de son ID
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        $query = $this->pdo
            ->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $query->execute([$id]);
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        return $query->fetch() ?: null;
    }


    /**
     * update les champs ds BD
     *
     * @param  int $id
     * @param  array $params
     *
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        $fieldQuery = $this->buildFieldQuery($params);
        $params["id"] = $id;
        $statement = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id");
        return $statement->execute($params);
    }

    /**
     * Crée un nouvel enregistrement
     *
     * @param array $params
     * @return bool
     */
    public function insert(array $params): bool
    {
        $fields = array_keys($params);
        $values = join(', ', array_map(function ($field) {
            return ':' . $field;
        }, $fields));
        $fields = join(', ', $fields);
        $statement = $this->pdo->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");
        return $statement->execute($params);
    }


    /**
     * delete article
     *
     * @param  int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM {$this->table}  WHERE id = ?");
        return $statement->execute([$id]);
    }


    private function buildFieldQuery(array $params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }





    /**
     * Get nom de l'entité utilisé
     *
     * @return  string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * Get nom de la table en BDD
     *
     * @return  string
     */
    public function getTable(): string
    {
        return $this->table;
    }



    /**
     * Vérifie qu'un enregistrement existe
     * @param $id
     * @return bool
     */
    public function exists($id): bool
    {
        $statement = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE id = ?");
        $statement->execute([$id]);
        return $statement->fetchColumn() !== false;
    }

    /**
     * Get the value of pdo
     *
     * @return  \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}
