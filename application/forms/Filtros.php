<?php

class Application_Form_Filtros extends Zend_Form {

    public function init() {
        /* Form Elements & Other Definitions Here ... */



        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'div_form'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'p', 'class' => 'element-group')),));

        $reportes = array(
            'Clientes' => array(
                '1' => 'Ventas por Cliente',
                '2' => 'Usuario mas Activo Ventas',
                '6' => 'Usuario mas Activo Publicaciones'),
            'Productos' => array(
                '3' => '10 Prod. mas Vendidos',
                '4' => '10 Prod. menos Vendidos'),
            'Publicaciones' => array(
                '5' => 'Publicaciones mas Comentadas'
            )
        );

        $this->addElement('select', 'tipo', array('required' => true, 'multioptions' => $reportes));

        $clientes = new Application_Model_DbTable_Usuarios();

        $this->addElement('select', 'cliente', array('multiOptions' =>
            array(
                '0' => 'Todos')));
        $this->cliente->addmultiOptions($clientes->select_clientes())
        ;

        $this->addElement('text', 'from');

        $this->addElement('text', 'to');


        $this->addElement('submit', 'Ejecutar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}

