<?php

/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : FORM - llamado desde Administrador Controller
  Author : Josefina - Cristian
  Description : Formulario Buscar Cliente (Confirmar Venta)
  Created : -
  Modified : 26/06  - Validacion Hecha
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */

class Application_Form_Buscarcliente extends Zend_Form {

    public function init() {

        //$this->setAction('/administrador/confirmarventa');

        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => ''))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'p', 'class' => 'element-group')),
        ));
        $clientes = new Application_Model_DbTable_Usuarios();

        $this->addElement('select', 'cliente', array());
        $this->cliente->addmultiOptions($clientes->select_clientes());

        $this->addElement('text', 'puntos', array(
            'readonly' => 'Readonly'
        ));

        $cliente = $clientes->traerdatosclientes();
        foreach ($cliente as $valor) {
            $id_primer_cliente = $valor->id_usuario;
            break;
        }
        $modeloModel = new Application_Model_DbTable_Usuarios();
        $rowset = $modeloModel->getAsKeyValue($id_primer_cliente);
        $this->puntos->setValue($rowset);
        
     

        $descuento = new Zend_form_element_text('descuento');
        $this->addElement($descuento);
        $validatorDigit = new Zend_Validate_Digits();
        $this->descuento->addValidator($validatorDigit);
        $this->descuento->setErrorMessages(array('messages' => 'El campo nombre solo puede contener Numeros'));


        $this->addElement('textarea', 'observacion', array());
        $this->addElement('text', 'mesa', array(
            'required' => true,
            'filters' => array('StringToLower'),
        ));
        $this->mesa->addValidator($validatorDigit);
        $this->mesa->setErrorMessages(array('messages' => 'El campo nombre solo puede contener Numeros'));

        $this->addElement('submit', 'Agregar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'div')))));
    }

}

