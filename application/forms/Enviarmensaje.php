<?php

class Application_Form_Enviarmensaje extends Zend_Form {

    public function init() {


        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'div_form'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'p', 'class' => 'element-group')),));

        $this->addElement('text', 'nombre', array(
            'value' => '',
            'required' => true,
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));

        $this->addElement('textarea', 'mensaje');

        $this->addElement('text', 'email', array(
            'required' => true,
            'filters' => array('StringTrim', 'StripTags', 'StringToLower'),
        ));


        $this->addElement('submit', 'Enviar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}
