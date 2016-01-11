<?php

class Application_Form_Editarreserva extends Zend_Form {

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

        $this->addElement('text', 'nombre_cliente', array(
            'value' => '',
        ));

        $this->addElement('text', 'personas', array(
            'value' => '',
        ));

        $this->addElement('text', 'observacion', array(
            'value' => '',
        ));

        $this->addElement('text', 'email', array(
        ));

        $this->addElement('text', 'fecha_reserva', array(
        ));

        $this->addElement('text', 'fecha_realizacion', array(
        ));

        $tipopublicacion = new Application_Model_DbTable_EstadoReservas();
        $tipo = $tipopublicacion->traerestadoreserva();

        $this->addElement('select', 'tipoestado', array(
            'multiOptions' => $tipo,
                )
        );
        $this->addElement('hidden', 'id_reserva', array(
            'value' => '',
        ));

        $this->addElement('submit', 'Confirmar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}
