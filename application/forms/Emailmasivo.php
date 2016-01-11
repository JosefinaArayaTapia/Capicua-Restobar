<?php

class Application_Form_Emailmasivo extends Zend_Form {

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

        $this->addElement('text', 'asunto', array('label' => 'Asunto',
            'value' => '',
            'required' => true,
        ));

        $this->addElement('textarea', 'mensaje', array(
            'label' => 'Descripcion',));

//        $element = new Zend_Form_Element_File('element');
//        $element->setRequired(true)
//                ->setLabel('Subir Imagen')
//                ->setValueDisabled(true)
//                ->addValidator('Extension', false, 'jpg,png,gif,jpeg');
//
//        $this->addElement($element);

        $this->addElement('submit', 'Enviar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}

