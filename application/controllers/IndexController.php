
<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        try {
            Zend_Session::start();
        } catch (Zend_Session_Exception $e) {
            session_start();
        }
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
                $view->apellido = $user->apellido;

                $view->tipo_user = $user->tipo_usuario;
                $view->whatever = $user->foto_perfil;
                $view->name = $user->nombre;
                $view->ultimo = $user->ultimo_acceso;
                $view->permiso = $permisoadmin;
            }
        }
    }

    public function indexAction() {

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
                $view->ultimo = $user->ultimo_acceso;
                $view->permiso = $permisoadmin;
            }
        }
        $modelpublica = new Application_Model_DbTable_Publicaciones();
        $this->view->publicaciones = $modelpublica->traerdosultimas();
    }

}

