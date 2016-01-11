<?php

class Application_Form_Login extends Zend_Form {

    public function init() {

        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'div_form'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'p', 'class' => 'element-group')),));

        $this->addElement(
                'text', 'email', array(
            'label' => 'E-mail:',
            'required' => true
        ));
        $this->email->addValidator('EmailAddress', TRUE);


        $this->addElement('password', 'password', array(
            'label' => 'Contraseña:',
            'required' => true
        ));

        $this->addElement(
                'submit', 'Ingresar', array(
            'ignore' => true,
            'class' => 'submit-group',
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'botonsubmit'))))
        );
    }

}