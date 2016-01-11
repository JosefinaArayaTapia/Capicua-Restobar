<?php

/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : FORM - llamado desde Administrador Controller
  Author : Josefina - Cristian
  Description : Formulario Agregar tipo de Publicacion
  Created : -
  Modified : 26/06  - Validacion Hecha
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */

class Application_Form_Agregartipopublicacion extends Zend_Form {

    public function init() {

        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'div_form'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'p', 'class' => 'element-group')),));

        $this->addElement('text', 'nombre_tipo', array('label' => 'Nombre Tipo ',
            'required' => true,
        ));
        $validator = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));
        $this->nombre_tipo->addValidator($validator);
        $this->nombre_tipo->addValidator('stringLength', false, array(1, 10));
        $this->nombre_tipo->setErrorMessages(array('messages' => 'El campo nombre solo puede contener letras y un largo de 10'));


        $this->addElement('submit', 'Enviar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}
