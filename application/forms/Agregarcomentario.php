<?php
/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : FORM - llamado desde Publicaciones Controller
  Author : Josefina - Cristian
  Description : Formulario Agregar Comentatario
  Created : -
  Modified : 26/06  - Validacion Hecha
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */
class Application_Form_Agregarcomentario extends Zend_Form {

    public function init() {
        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => ''))
                ->addDecorator('Form');

        $this->setElementDecorators(array(array('ViewHelper'),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'div', 'class' => '')),));

        $this->addElement('textarea', 'inputField', array(
            'id' => 'inputField',
            'tabindex' => '1',
            'rows' => '2',
            'cols' => '40',
            'required' => true,
            'filters' => array('StripTags'),
        ));

        $postid = new Zend_Form_Element_Hidden('postid');
        $this->addElement($postid);
        $iduser = new Zend_Form_Element_Hidden('iduser');
        $this->addElement($iduser);
        $this->addElement('submit', 'comenta', array(
            'ignore' => true,
            'id' => 'update_button',
//            'disabled' => 'disabled',
            'value' => 'Comentar',
            'class' => 'button4',
        ));
    }

}

