<?php

/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : FORM - llamado desde Administrador Controller
  Author : Josefina - Cristian
  Description : Formulario Agregar Producto
  Created : -
  Modified : 26/06  - Validacion Hecha
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */

class Application_Form_Agregarproducto extends Zend_Form {

    public function init() {

        $this->setMethod('post')->setAttrib('enctype', 'multipart/form-data');

        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'div_form'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'p', 'class' => 'element-group')),));

        $this->addElement('text', 'nombre', array('label' => 'Nombre',
            'value' => '',
            'required' => true,
            'filters' => array('StripTags')
        ));
        $validator = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));
        $this->nombre->addValidator($validator);
        $this->nombre->setErrorMessages(array('messages' => 'El campo nombre solo puede contener letras'));


        $this->addElement('textarea', 'descripcion', array(
            'label' => 'Descripcion',));


        $menus = new Application_Model_DbTable_TipoProductos();
        $tipo_menu = $menus->select_tipoProducto();

        $this->addElement('select', 'id_tipo_producto', array(
            'label' => 'Tipo de Producto',
        ));
        $this->id_tipo_producto->addmultiOptions($tipo_menu);

        $this->addElement('text', 'precio', array('label' => 'Precio',
            'value' => '',
            'required' => true,
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));
        $validatorDigit = new Zend_Validate_Digits();
        $this->precio->addValidator($validatorDigit);
        $this->precio->setErrorMessages(array('messages' => 'El campo precio solo puede contener Numeros'));

        $this->addElement('text', 'puntos_producto', array(
            'label' => 'Puntos',
            'value' => '',
            'required' => true,
            'filters' => array('StringTrim', 'StripTags', 'StringToLower')));

        $this->puntos_producto->addValidator($validatorDigit);
        $this->puntos_producto->setErrorMessages(array('messages' => 'El campo puntos solo puede contener Numeros'));

        $this->addElement('hidden', 'imagen');


        $element = new Zend_Form_Element_File('element');
        $element->setRequired(false)
                ->setLabel('Subir Imagen')
                ->setValueDisabled(true)
                ->addValidator('Extension', false, 'jpg,png,gif,jpeg');


        $this->addElement($element);

        $this->addElement('submit', 'Agregar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}

