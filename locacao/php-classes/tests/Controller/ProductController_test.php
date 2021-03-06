<?php

namespace Locacao\tests\Controller;
use \Locacao\Controller\ProductController;

use PHPUnit\Framework\TestCase;

class ProductController_test extends TestCase
{
    public function testCreateCode()
    {
        $arr["categoria"] = 1;
       
        $arr = [
            'categoria'=>1,
            'codCategory'=>'001',
            'tipo1'=>'01',
            'tipo2'=>'01',
            'tipo3'=>'01',
            'tipo4'=>'01'
        ];
        $p = new ProductController();
        $p->setcodCategory($arr["codCategory"]);

        //$p->setData($arr);

        $this->assertEquals('001.01.01.01.01', $p->createCode($arr));
    }

    public function testVerifyFields()
    {
        $arr = [
            'vlBaseAluguel'=>200,
            'categoria'=>'1-001',
            'tipo1'=>'1-01',
            'tipo2'=>'2-02',
            'tipo3'=>'3-03',
            'tipo4'=>'4-04'
        ];

        $p = new ProductController();
        $error = $p->verifyFields($arr);
        $aux = json_decode($error);

        $this->assertEquals(false, $aux->error);
    }

    public function testVerifyCodeExists() {
        echo "testVerifyCodeExists <br>";

        $arr = [
            'categoria'=>1,
            'codCategory'=>'001',
            'tipo1'=>'01',
            'tipo2'=>'01',
            'tipo3'=>'01',
            'tipo4'=>'01'
        ];

        $p = new ProductController();
        $aux = $p->verifyCodeExists($arr);
        print_r($aux);
        $this->assertEquals(false, json_decode($aux)->error);
    }

}