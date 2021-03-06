<?php

/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : FORM - llamado desde Administrador Controller
  Author : Josefina - Cristian
  Description : Formulario Agregar Publicidad
  Created : -
  Modified : 26/06  - Validacion Hecha
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */

class Application_Form_Agregarpublicidad extends Zend_Form {

    public function init() {

        $this->setMethod('post')->setAttrib('enctype', 'multipart/form-data');

        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'div_form'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'div', 'class' => 'element-group')),));

        $this->addElement('text', 'titulo', array('label' => 'Titulo',
            'value' => '',
            'required' => true,
        ));

        $this->titulo->addValidator('stringLength', false, array(1, 30));
        $this->titulo->setErrorMessages(array('messages' => 'El campo titulo solo puede contener letras y un largo de 20'));

        $this->addElement('textarea', 'contenido', array(
            'label' => 'Contenido',));

   
        $this->addElement('text', 'datepicker', array('label' => 'Fecha Vigencia',
            'required' => true,
            ));

        $this->addElement('hidden', 'imagen');
        $tipopublicacion = new Application_Model_DbTable_TipoPublicaciones();
        $tipo = $tipopublicacion->selecttipopublicaciones();

        $this->addElement('select', 'tipopublicacion', array(
            'label' => 'Tipo de Publicacion',
            'multiOptions' => $tipo,
                )
        );

        // 1° version  
        $element = new Zend_Form_Element_File('element');
        $element->setRequired(false)
                ->setLabel('Subir Imagen')
                ->setValueDisabled(true)
                ->addValidator('Extension', false, 'jpg,png,gif,jpeg');

        $this->addElement($element);


        $this->addElement('submit', 'Aceptar', array('ignore' => true,
            'decorators' => array(array('ViewHelper'),
                array('HtmlTag', array('tag' => 'p', 'class' => 'submit-group')))));
    }

}

