<?php

class TipoproductoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $modelo =new Application_Model_DbTable_TipoProductos();
        $numero_tipos=$modelo->contadortipoproducto();
        $this->view->tipoproducto= $numero_tipos;
      
    }


}

