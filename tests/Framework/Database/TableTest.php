<?php
namespace Tests\Framework\Database;

use PDO;
use stdClass;
use Framework\Database\Table;
use PHPUnit\Framework\TestCase;



class TableTest extends TestCase
{

/**
 * @var Table
 */
    private $table;



    public function setUp(){
        $pdo = new \PDO('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
        ]);
    // creation de la bd pour sqlite
        $pdo->exec('CREATE TABLE test (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255)
            )');

        $this->table = new Table($pdo);
        $refletion =  new \ReflectionClass($this->table);
        $property = $refletion->getProperty('table');
        $property->setAccessible(true);
        $property->setValue($this->table, 'test');
    }



    public function testFind()
    {
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a1")');
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a2")');
        $test = $this->table->find(1);
        $this->assertInstanceOf(\stdClass::class, $test);
        $this->assertEquals('a1', $test->name);
    }



    public function testFindList()
    {
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a1")');
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a2")');
        $test = $this->table->findList();
        $this->assertEquals(['1' => 'a1', '2' => 'a2'], $this->table->findList());
    }


    public function testExists()
    {
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a1")');
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a2")');
        $test = $this->table->findList();
        $this->assertTrue($this->table->exists(1));
        $this->assertTrue($this->table->exists(2));
        $this->assertFalse($this->table->exists(3));
    }


    public function testFindAll()
    {
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a1")');
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a2")');
        $categories = $this->table->findAll();
        $this->assertCount(2, $categories);
        $this->assertInstanceOf(\stdClass::class, $categories[0]);
        $this->assertEquals('a1', $categories[0]->name);
        $this->assertEquals('a2', $categories[1]->name);
    }


    public function testFindBy()
    {
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a1")');
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a2")');
        $this->table->getPdo()->exec('INSERT INTO test (name) VALUES ("a1")');
        $category = $this->table->findBy('name', 'a1');

        $this->assertInstanceOf(\stdClass::class, $category);
        $this->assertEquals(1, (int)$category->id);
    }




    
}

// ./vendor/bin/phpunit tests/Framework/Database/TableTest.php --colors
