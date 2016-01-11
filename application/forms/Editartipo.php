<?php

class Application_Form_Editartipo extends Zend_Form {

    public function init() {

        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'div_form'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'p', 'class' => 'element-group')),));

        $this->addElement('text', 'nombre_tipo', array('label' => 'Nombre Tipo o Menu',
            'value' => '',
            'required' => true,
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));

        $this->addElement('select', 'tipo_menu', array('label' => 'Es menu',
            'value' => '',
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
            'multiOptions' => array(
                '1' => 'SI',
                '0' => 'NO',
                )));



        $this->addElement('submit', 'Enviar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}

