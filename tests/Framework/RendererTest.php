<?php

namespace Tests\Framework;

use Framework\Renderer;
use PHPUnit\Framework\TestCase;


class RendererTest extends TestCase{

    private $renderer;

    public function setUp()
    {
        $this->renderer = new Renderer();
        $this->renderer->addPath( __DIR__ . '/views');

    }


    public function testRenderTheRightPath(){
        $this->renderer->addPath('blog', __DIR__ . '/views');
        $content = $this->renderer->render('@blog/demo');
        $this->assertEquals('salut les gens', $content);
    }

    public function testRenderTheDefaultPath(){
        $content = $this->renderer->render('demo');
        $this->assertEquals('salut les gens', $content);
    }

    public function testRenderWithParams(){
        $content = $this->renderer->render('demoparams', ['nom' => 'Marc']);
        $this->assertEquals('salut Marc', $content);
    }


    public function testGlobalParameters(){
        $this->renderer->addGlobal('nom','Marc');
        $content = $this->renderer->render('demoparams', ['nom' => 'Marc']);
        $this->assertEquals('salut Marc', $content);
    }


    
    
}