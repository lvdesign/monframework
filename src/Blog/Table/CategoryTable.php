<?php

namespace App\Blog\Table;

use App\Blog\Entity\Post;
use Pagerfanta\Pagerfanta;
use Framework\Database\Table;
use Framework\Database\PaginatedQuery;

class CategoryTable extends Table
{



    //protected $entity = Post::class;

    protected $table = 'categories';
}
