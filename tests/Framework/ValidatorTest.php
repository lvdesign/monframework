<?php
namespace Tests\Framework;

use Framework\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    private function makeValidator(array $params)
    {
        return new Validator($params);
    }

// verifie Required 
    public function testRequiredIfFail(){
    $errors = $this->makeValidator(['name' => 'joe'])
        ->required('name', 'content')
        ->getErrors();

        $this->assertCount(1, $errors);
    }

// Valeur Not Empty
    public function testNotEmpty(){
        $errors = $this->makeValidator(['name' => 'joe', 'content' => ''])
            ->notEmpty( 'content')
            ->getErrors();
    
            $this->assertCount(1, $errors);
        }

// required OK
    public function testRequiredIfSuccess(){
    $errors = $this->makeValidator([
        'name' => 'joe',
        'content' => 'content'
    ])
        ->required('name', 'content')
        ->getErrors();
        $this->assertCount(0, $errors);
    }

    // Slug OK
    public function slugSuccess(){
        $errors = $this->makeValidator([
            'slug' => 'joe-joe'
        ])
            ->required('slug')
            ->getErrors();
            $this->assertCount(0, $errors);
            $this->assertEquals("joe-joe", $errors);
    }

    // Slug Erreur ds ecriture
    public function testSlugError()
    {
        $errors = $this->makeValidator([
            'slug'  => 'aze-aze-azeAze34',
            'slug2' => 'aze-aze_azeAze34',
            'slug3' => 'aze--aze-aze'
        ])
            ->slug('slug')
            ->slug('slug2')
            ->slug('slug3')
            ->slug('slug4')
            ->getErrors();
        $this->assertCount(3, $errors);
    }


    // 
    public function testLength()
    {
        $params = ['slug' => '123456789'];
        $this->assertCount(0, $this->makeValidator($params)->length('slug', 3)->getErrors());
        $errors = $this->makeValidator($params)->length('slug', 12)->getErrors();
        $this->assertCount(1, $errors);
        //$this->assertEquals('Le champs slug doit contenir plus de 12 caractères', (string)$errors['slug']);
        $this->assertCount(1, $this->makeValidator($params)->length('slug', 3, 4)->getErrors());
        $this->assertCount(0, $this->makeValidator($params)->length('slug', 3, 20)->getErrors());
        $this->assertCount(0, $this->makeValidator($params)->length('slug', null, 20)->getErrors());
        $this->assertCount(1, $this->makeValidator($params)->length('slug', null, 8)->getErrors());
    }


    public function testDateTime()
    {
        $this->assertCount(0, $this->makeValidator(['date' => '2012-12-12 11:12:13'])->dateTime('date')->getErrors());
        $this->assertCount(0, $this->makeValidator(['date' => '2012-12-12 00:00:00'])->dateTime('date')->getErrors());
        $this->assertCount(1, $this->makeValidator(['date' => '2012-21-12'])->dateTime('date')->getErrors());
        $this->assertCount(1, $this->makeValidator(['date' => '2013-02-29 11:12:13'])->dateTime('date')->getErrors());
    }







}


// ./vendor/bin/phpunit tests/Framework/ValidatorTest.php --colors