<?php

/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : FORM - llamado desde Administrador Controller
  Author : Josefina - Cristian
  Description : Formulario Agregar Cliente
  Created : - 
  Modified : 26/06  - Validacion Hecha
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */
class Application_Form_Agregarcliente extends Zend_Form {

    public function init() {


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
            'filters' => array('StripTags'),
        ));

        $validator = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));

        $this->nombre->addValidator($validator);
        $this->nombre->setErrorMessages(array('messages' => 'El campo nombre solo puede contener letras'));

        $this->addElement('text', 'apellido', array('label' => 'Apellido',
            'value' => '',
            'required' => true,
            'filters' => array('StripTags'),
        ));
        $this->apellido->addValidator($validator);

        $this->apellido->setErrorMessages(array('messages' => 'El campo nombre solo puede contener letras'));


        $this->addElement('text', 'email', array('label' => 'Email',
            'value' => '',
            'required' => true,
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));
        $this->email->addValidator('EmailAddress', TRUE);
        $db_lookup_validator = new Zend_Validate_Db_NoRecordExists('usuarios', 'email');
        $this->email->addValidator($db_lookup_validator)->setErrorMessages(array('messages' => 'Este Email ya esta siendo coupado'));


        $this->addElement('submit', 'Agregar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}

