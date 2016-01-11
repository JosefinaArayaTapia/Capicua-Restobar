<?php

require 'ZendY/ZendY_Form.php';

class Application_Form_Agregarventa extends ZendY_Form {

    public function init() {

        $this->setAction('/administrador/confirmarventa')
                ->setMethod('post');
        
        $model = new Application_Model_DbTable_CartaProductos();
        $productos = $model->getproductos();

        foreach ($productos as $val) {
            $this->addElement('hidden', $val->id_producto, array(
                'required' => false,
                'pimage' => '/images/carta/' . $val->imagen,
                'pprice' => $val->precio,
                'pdesc' => $val->descripcion,
                'pcategory' => $val->nombre_tipo,
                'pname' => $val->nombre,
                'pid' => $val->id_producto,
                'value' => '',
                'disableLoadDefaultDecorators' => true,
                'decorators' => parent::$_standardElementDecoratorClearLeft
            ));
        }
    }

}

