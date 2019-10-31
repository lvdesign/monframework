<?php
namespace App\Blog\Table;

use App\Blog\Entity\Post;
use Pagerfanta\Pagerfanta;
use Framework\Database\Query;
use Framework\Database\Table;
use Framework\Database\PaginatedQuery;

class PostTable extends Table
{

    
    protected $entity = Post::class;

    protected $table = 'posts';


    public function findAll(): Query
    {
        $category = new CategoryTable($this->pdo);
        return $this->makeQuery()
        ->join($category->getTable() . ' as c', 'c.id = p.category_id')
        ->select('p.*, c.name as category_name, c.slug as category_slug')
        ->order('p.created_at DESC');
    }


    public function findPublic(): Query
    {
        return $this->findAll()
            ->where('p.published=1')
            ->where('p.created_at < NOW()'); // date
    }

 // CategoryShowAction
    public function findPublicForCategory(int $id): Query
    {
        return $this->findPublic()->where("p.category_id = $id");
    }


// PostShowAction
    public function findWithCategory(int $postId): Post
    {
        return $this->findPublic()->where("p.id = $postId")->fetch();
    }



    //front
/*     public function findPaginatedPublic(int $perPage, int $currentPage): PagerFanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            "SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM posts as p
            LEFT JOIN categories as c ON c.id = p.category_id
            ORDER BY p.created_at DESC",
            "SELECT COUNT(id) FROM {$this->table}",
            $this->entity
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    } */



    /* public function findPaginatedPublicForCategory(int $perPage, int $currentPage, int $categoryId): PagerFanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            "SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM posts as p
            LEFT JOIN categories as c ON c.id = p.category_id
            WHERE p.category_id = :category
            ORDER BY p.created_at DESC",
            "SELECT COUNT(id) FROM {$this->table}  WHERE category_id = :category",
            $this->entity,
            ['category' => $categoryId]
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }
 */
    
    
   /*  public function findWithCategory(int $id)
    {
        return $this->fetchOrFail('
            SELECT p.*, c.name category_name, c.slug category_slug
            FROM posts as p
            LEFT JOIN categories as c ON c.id = p.category_id
            WHERE p.id = ?
        ', [$id]);
    }

    // admin
    protected function paginationQuery()
    {
        //return parent::paginationQuery() . " ORDER BY created_at DESC";

        return "SELECT p.id, p.name,c.name category_name
        FROM {$this->table} AS p
        LEFT JOIN categories AS c ON p.category_id = c.id
        ORDER BY created_at DESC";
    } */
}
