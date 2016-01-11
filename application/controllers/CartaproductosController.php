<?php

/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : Administrador Controller
  Author : Josefina - Cristian
  Description :
  Created :
  Modified :
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */

class CartaproductosController extends Zend_Controller_Action {

    public function init() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $info = Zend_Auth::getInstance()->getIdentity();
            $model = new Application_Model_DbTable_Usuarios();
            $usser = $model->traerdatoscliente($info);
            $this->view->datosuser = $usser;
            $layout = Zend_Layout::getMvcInstance();
            $view = $layout->getView();
            foreach ($usser as $user) {
                $id_user = $user->id_usuario;
                $tipo_user = $user->tipo_usuario;
                $permisoadmin = $user->permiso;
                $view->tipo_user = $user->tipo_usuario;
                $view->whatever = $user->foto_perfil;
                $view->name = $user->nombre;
                $view->apellido = $user->apellido;

                $view->ultimo = $user->ultimo_acceso;
                $view->permiso = $permisoadmin;
            }
        }
    }

    public function indexAction() {


        $model = new Application_Model_DbTable_CartaProductos();
        $modelotipo = new Application_Model_DbTable_TipoProductos();
        $this->view->nombrestipo = $modelotipo->nombretipo();
        $this->view->cartaproductos = $model->getproductos();
    }

    public function mostrarmenuAction() {


        $modelo = new Application_Model_DbTable_TipoProductos();
        $modelomenus = new Application_Model_DbTable_CartaProductos();

        $this->view->menus = $modelo->nombretipomenu();
        $this->view->nombresmenu = $modelomenus->nombremenus();
    }

    public function getAll() {
        $modelo = new Application_Model_DbTable_CartaProductos();
        $this->view->menus = $modelo->listarmenus();
    }

}