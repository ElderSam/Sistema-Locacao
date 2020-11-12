<?php

namespace Locacao;
use \Locacao\Controller\ProductController;

use PHPUnit\Framework\TestCase;

class ProductController_test extends TestCase
{
    public function testCreateCode()
    {
        $p = new ProductController();

        $_POST["categoria"] = 1;
        $_POST["codCategory"] = '001';
        $_POST["tipo1"] = '01';
        $_POST["tipo2"] = '01';
        $_POST["tipo3"] = '01';
        $_POST["tipo4"] = '01';

        $p->setData($_POST);
        $p->createCode();
        
        $this->assertEquals('001.01.01.01.01', $_POST["codigoGen"]);
    }
}