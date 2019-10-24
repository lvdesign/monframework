<?php

namespace Framework\Database;

use Pagerfanta\Pagerfanta;

class Table
{

    /**
     * @var \PDO
     */
    protected $pdo;

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
     * findAll recupere tous les enregistrements
     *
     * @return array
     */
    public function findAll(): array
    {
        $query = $this->pdo->query("SELECT * FROM  {$this->table} ");
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        } else {
            $query->setFetchMode(\PDO::FETCH_OBJ);
        }
       
        return $query->fetchAll();
    }


    /**
     * findBy
     *
     * @param  string $field
     * @param  string $value
     *
     * @return array
     *
     * @throws NoRecordException
     */
    public function findBy(string $field, string $value)
    {
        return $this->fetchOrFail("SELECT * FROM  {$this->table} WHERE $field= ? ", [$value]);
    }


     /**
     * Récupère un élément à partir de son ID
     *
     * @param int $id
     * @return mixed
     *
     * @throws NoRecordException
     */
    public function find(int $id)
    {
        return $this->fetchOrFail("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }



    /**
     * count
     * @var
     * @return int
     */
    public function count(): int
    {
        return $this->fetchColumn("SELECT COUNT(id) FROM {$this->table}");
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
        $query = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id");
        return $query->execute($params);
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
        $query = $this->pdo->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");
        return $query->execute($params);
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
        $query = $this->pdo->prepare("DELETE FROM {$this->table}  WHERE id = ?");
        return $query->execute([$id]);
    }


    private function buildFieldQuery(array $params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }

    /**
     * Permet d'éxécuter une requête et de récupérer le premier résultat
     *
     * @param string $query
     * @param array $params
     * @return mixed
     * @throws NoRecordException
     */
    protected function fetchOrFail(string $query, array $params = [])
    {
        $query = $this->pdo->prepare($query);
        $query->execute($params);
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        $record = $query->fetch();
        if ($record === false) {
            throw new NoRecordException();
        }
        return $record;
    }

    /**
     * fetchColumn recupere la premierer colonne
     *
     * @param  string $query
     * @param  array $params
     *
     * @return void
     */
    protected function fetchColumn(string $query, array $params = [])
    {
        $query = $this->pdo->prepare($query);
        $query->execute($params);
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        return $query->fetchColumn();
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
        $query = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE id = ?");
        $query->execute([$id]);
        return $query->fetchColumn() !== false;
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
