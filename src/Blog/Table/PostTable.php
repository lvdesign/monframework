<?php

namespace App\Blog\Table;

use App\Blog\Entity\Post;
use Pagerfanta\Pagerfanta;
use Framework\Database\Table;
use Framework\Database\PaginatedQuery;

class PostTable extends Table
{

    /**
     * @var \PDO
     */
    private $pdo;


    protected $entity = Post::class;

    protected $table = 'posts';


    protected function paginationQuery()
    {
        //return parent::paginationQuery() . " ORDER BY created_at DESC";

        return "SELECT p.id, p.name,c.name categry_name 
        FROM {$this->table} AS p
        LEFT JOIN categories AS c ON p.category_id = c.id
        ORDER BY created_at DESC";
    }
}
