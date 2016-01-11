<?php

class Application_Form_Agregaradministrador extends Zend_Form {

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
        ));

        $this->addElement('text', 'apellido', array('label' => 'Apellido',
            'value' => '',
            'required' => true,
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));

        $this->addElement('text', 'email', array('label' => 'Email',
            'value' => '',
            'required' => true,
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));

        $this->addElement('password', 'password', array('label' => 'Contraseña',
            'value' => '',
            'required' => true,
        ));


        $this->addElement('select', 'permiso', array(
            'label' => 'Tipo Administrador', 'value' => 'blue',
            'multiOptions' => array('1' => 'SuperAdministrador', '2' => 'Administrador'),));

        $this->addElement('submit', 'Agregar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}
