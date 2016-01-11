<?php

class Application_Form_Hacerreserva extends Zend_Form {

    public function init() {


        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'div_form'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'p', 'class' => 'element-group')),));

        $this->addElement('text', 'personas', array('label' => 'Cantidad de Personas',
            'value' => '',
            'required' => true,
        ));

        $validatorDigit = new Zend_Validate_Digits();
        $this->personas->addValidator($validatorDigit);
        $this->personas->setErrorMessages(array('messages' => 'El campo Personas solo puede contener Numeros'));

        $this->addElement('text', 'datepicker', array(
            'label' => 'Fecha a Reservar',
            'required' => true));

        $this->addElement('textarea', 'observacion', array('label' => 'Observacion',
            'value' => '',
            
        ));

        $this->addElement('submit', 'Enviar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}