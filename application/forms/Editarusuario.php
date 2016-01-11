<?php

class Application_Form_Editarusuario extends Zend_Form {

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
        $validator = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));
        $this->nombre->addValidator($validator);
        $this->nombre->setErrorMessages(array('messages' => 'El campo nombre solo puede contener letras'));


        $this->addElement('text', 'apellido', array('label' => 'Apellido',
            'value' => '',
            'required' => true,
        ));

        $this->apellido->addValidator($validator)->setErrorMessages(array('messages' => 'El campo nombre solo puede contener letras'));


        $this->addElement('text', 'telefono', array('label' => 'Telefono',
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));
        $this->telefono->addValidator('digits')->setErrorMessages(array('messages' => 'El campo nombre solo puede contener numeros'));

        $this->addElement('text', 'domicilio', array('label' => 'Domicilio',
        ));

        $this->addElement('hidden', 'foto_perfil');

        $element = new Zend_Form_Element_File('element');
        $element->setRequired(false)
                ->setLabel('Subir Imagen de Perfil')
                ->setValueDisabled(true)
                ->addValidator('Extension', false, 'jpg,png,gif,jpeg');

        $this->addElement($element);

        $this->addElement('submit', 'Enviar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}

