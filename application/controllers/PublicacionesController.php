<?php

class PublicacionesController extends Zend_Controller_Action {

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
                $view->apellido = $user->apellido;

                $view->tipo_user = $user->tipo_usuario;
                $view->whatever = $user->foto_perfil;
                $view->name = $user->nombre;
                $view->ultimo = $user->ultimo_acceso;
                $view->permiso = $permisoadmin;
            }
            Zend_Registry::set('permiso', $permisoadmin);
            Zend_Registry::set('tipo_user', $tipo_user);
        } else {

            Zend_Registry::set('permiso', NULL);
            Zend_Registry::set('tipo_user', NULL);
        }
    }

    public function indexAction() {

        $model = new Application_Model_DbTable_Publicaciones();
        $publicaciones = $model->getAll();

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($publicaciones);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
    }

    public function agregarcomentarioAction() {
        $this->_helper->layout()->disableLayout();
        $data = $this->_request->getPost();
        if (Zend_Registry::get('tipo_user')) {
            $tipo_user = Zend_Registry::get('tipo_user');
        } else {
            $tipo_user = NULL;
        }
        if ($data) {
            $postid = $data['postid'];
            $comment = $data['inputField'];
            $iduser = $data['iduser'];
            $fecha = new Zend_Db_Expr('NOW()');

            $modelcoment = new Application_Model_DbTable_Comentarios();
            $modelcoment->agregarcomentario($comment, $fecha, $postid, $iduser);

            $model = new Application_Model_DbTable_Usuarios();
            $user = $model->traerdatosclienteID($iduser);
            foreach ($user as $usser) {
                $nombre = $usser->nombre;
                $foto = $usser->foto_perfil;
            }
        }
        $this->view->tipo_user = $tipo_user;
        $this->view->postid = $postid;
        $this->view->comment = $comment;
        $this->view->nombre = $nombre;
        $this->view->foto = $foto;
    }

    public function postAction() {

        if (!$this->_getParam('nn')) {
            return $this->_redirect('/publicaciones');
        }
        if (Zend_Registry::get('tipo_user')) {
            $tipo_user = Zend_Registry::get('tipo_user');
        } else {
            $tipo_user = NULL;
        }
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $info = Zend_Auth::getInstance()->getIdentity();
            $model = new Application_Model_DbTable_Usuarios();
            $usser = $model->traerdatoscliente($info);
            $this->view->datosuser = $usser;
            $layout = Zend_Layout::getMvcInstance();
            $view = $layout->getView();
            $fotoperfil = '';
            $id_user = '';
            foreach ($usser as $user) {
                $id_user = $user->id_usuario;
                $view->whatever = $user->foto_perfil;
                $fotoperfil = $user->foto_perfil;
                $view->name = $user->nombre;
            }
        } else {
            $id_user = NULL;
            $fotoperfil = NULL;
        }
        $form = new Application_Form_Agregarcomentario();
        $modelpublicaciones = new Application_Model_DbTable_Publicaciones();
        $modelocomentarios = new Application_Model_DbTable_Comentarios();
        $publicacion = $modelpublicaciones->mostrarunapublicacion($this->_getParam('nn'));
        $numerocomment = $modelocomentarios->contarcomentariosunapublicacion($this->_getParam('nn'));
        $modelcomm = new Application_Model_DbTable_Comentarios();


        $this->view->form = $form;
        $this->view->tipo_user = $tipo_user;
        $this->view->numerocomentarios = $numerocomment;
        $posts = $modelcomm->todosloscomentarios();


        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($posts);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->comm = $paginator;
        $this->view->publicacion = $publicacion;
        $this->view->fotoperfil = $fotoperfil;
        $this->view->id_user = $id_user;
    }

    public function eliminarcomentarioAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administrador/listarpublicaciones');
        }
        $model = new Application_Model_DbTable_Comentarios();
        $row = $model->obtenerRow($this->_getParam('id'));
       
        if ($row) {
            $row->delete();
        }
        return $this->_redirect('/publicaciones');
    }

}








