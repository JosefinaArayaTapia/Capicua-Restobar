<?php

/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : FORM - llamado desde Usuario Controller
  Author : Josefina - Cristian
  Description : Formulario Registro Cliente
  Created : -
  Modified : 26/06  - Validacion Hecha
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */

class Application_Form_Registrarcliente extends Zend_Form {

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

        $this->addElement('text', 'nombre', array('label' => 'Nombre * ',
            'required' => true,
            'filters' => array('StripTags'),
        ));

        $validator = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));
        $this->nombre->setAttrib('placeholder', 'Nombre');
        $this->nombre->addValidator($validator);
        $this->nombre->setErrorMessages(array('messages' => 'El campo nombre solo puede contener letras'));

        $this->addElement('text', 'apellido', array('label' => 'Apellido  *',
            'value' => '',
            'required' => true,
            'filters' => array('StripTags',),
        ));
        $this->apellido->setAttrib('placeholder', 'Apellido');
        $this->apellido->addValidator($validator)->setErrorMessages(array('messages' => 'El campo nombre solo puede contener letras'));

        $this->addElement('text', 'telefono', array('label' => 'Telefono',
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));
        $this->telefono->addValidator('digits')->setErrorMessages(array('messages' => 'El campo nombre solo puede contener numeros'));


        $this->addElement('text', 'domicilio', array('label' => 'Domicilio',
            'filters' => array('StringTrim', 'StripTags')
        ));

        $this->addElement('password', 'contraseña', array('label' => 'Contraseña *',
            'filters' => array('StringTrim', 'StripTags')
        ));
        
        $this->contraseña->addValidator('stringLength', false, array(6, 50))->setErrorMessages(array('messages' => 'La Contraseña debe tener como minimo 6 caracteres'));

        $this->addElement('password', 'verifypassword', array(
            'label' => 'Verifica la Contraseña',
            'required' => true,
            'validators' => array(
                array('identical', true, array('contraseña'))
        )));

        $this->addElement('text', 'email', array('label' => 'Email *',
            'required' => true,
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));
                $this->email->setAttrib('placeholder', 'Email@Host.cl');

        $this->email->addValidator('EmailAddress', TRUE);
        $db_lookup_validator = new Zend_Validate_Db_NoRecordExists('usuarios', 'email');
        $this->email->addValidator($db_lookup_validator);


        $element = new Zend_Form_Element_File('element');
        $element->setRequired(false)
                ->setLabel('Subir Imagen de Perfil')
                ->setValueDisabled(true)
                ->addValidator('Extension', false, 'jpg,png,gif,jpeg');
        $this->addElement($element);

        $check = new Zend_Form_Element_Checkbox('check');
        $check->setLabel('Acepto el envio de promociones e Informacion por parte del Restaurant *')
                ->setUncheckedValue(null)
                ->setRequired()
                ->setDecorators(array('ViewHelper',
                    'Description',
                    'Errors',
                    array('Label',
                        array('separator' => ' ')),
                    array('HtmlTag', array('tag' => 'div', 'class' => 'terminos'))));
        $this->addElement($check);


        $this->addElement('submit', 'Enviar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}
