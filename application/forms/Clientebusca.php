<?php
/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : FORM - llamado desde Resportes Controller
  Author : Josefina - Cristian
  Description : Formulario Buscar Cliente (Reportes)
  Created : -
  Modified : 26/06  - Validacion Hecha
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */

class Application_Form_Clientebusca extends Zend_Form {

    public function init() {
        $this->setAction('/administrador/clienteventa');
        $this->setMethod('POST');


        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'div_form'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'p', 'class' => 'element-group')),
        ));
        $clientes = new Application_Model_DbTable_Usuarios();

        $this->addElement('select', 'cliente', array('Label'=>'Seleccione Cliente'));
        $this->cliente->addmultiOptions($clientes->select_clientes());


        $this->addElement('submit', 'Buscar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'div')))));
    }

}

