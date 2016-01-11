<?php

class UsuarioController extends Zend_Controller_Action {

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
            if ($permisoadmin == 1 || $permisoadmin == 2) {

                return $this->_redirect('/administrador');
            }
        }
    }

    public function indexAction() {
        
    }

    public function registrarclienteAction() {
        $form = new Application_Form_RegistrarCliente();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_DbTable_Usuarios();
                $nombre = $form->getValue('nombre');
                $apellido = $form->getValue('apellido');
                $telefono = $form->getValue('telefono');
                $domicilio = $form->getValue('domicilio');
                $contraseña = $form->getValue('contraseña');
                $email = $form->getValue('email');
                $foto = $form->getValue('element');
                $contraseña2 = md5($contraseña);
                if ($foto) {
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination(APPLICATION_PATH . '/../public/images/usuarios/');
                    try {
                        $upload->receive();
                    } catch (Zend_File_Transfer_Exception $e) {
                        $e->getMessage();
                    }
                } else {

                    $foto = 'default_user.png';
                }
                $visibilidad = 1;
                $puntos = 0;
                $tipo_usuario = 2;
                $permiso = 0;
                $fechaalta = new Zend_Db_Expr('NOW()');
                $ultimoacceso = new Zend_Db_Expr('NOW()');

                $model->save($nombre, $apellido, $email, $telefono, $domicilio, $foto, $puntos, $contraseña2, $tipo_usuario, $permiso, $fechaalta, $ultimoacceso, $visibilidad);
                return $this->_redirect('/usuario/login');
            }
        }
        $this->view->form = $form;
    }

    public function loginAction() {
        $form = new Application_Form_Login();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $authAdapter = new Zend_Auth_Adapter_DbTable();
                $authAdapter
                        ->setTableName('usuarios')
                        ->setIdentityColumn('email')
                        ->setCredentialColumn('password');
                $authAdapter
                        ->setIdentity(($form->getValue('email')))
                        ->setCredential(md5(($form->getValue('password'))));


                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {

                    $namespace = new Zend_Session_Namespace('Zend_Auth');
                    $namespace->setExpirationSeconds(3600); //Inactividad de 1 HR ACA !
                    return $this->_redirect('usuario');
                } else {
                    $form->email->addError('Datos Incorrectos');
                }
            }
        }
        $this->view->form = $form;
    }

    public function logoutAction() {

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $info = Zend_Auth::getInstance()->getIdentity();
            $model = new Application_Model_DbTable_Usuarios();
            $usser = $model->traerdatoscliente($info);
            foreach ($usser as $user) {
                $id_usuario = $user->id_usuario;
            }
        }
        $model = new Application_Model_DbTable_Usuarios();
        $model->ultimoacceso($id_usuario);
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('index');
    }

    public function editarperfilAction() {

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $info = Zend_Auth::getInstance()->getIdentity();
            $model = new Application_Model_DbTable_Usuarios();
            $usser = $model->traerdatoscliente($info);
            $this->view->datosuser = $usser;
            $layout = Zend_Layout::getMvcInstance();
            $view = $layout->getView();
            foreach ($usser as $user) {
                $view->whatever = $user->foto_perfil;
                $view->name = $user->nombre;
                $nombre = $user->nombre;
                $id_usuario = $user->id_usuario;
                $apellido = $user->apellido;
                $telefono = $user->telefono;
                $domicilio = $user->domicilio;
                $foto = $user->foto_perfil;
            }
            $form = new Application_Form_Editarusuario();
            //tienes el ID se busca la info de ese ID = $id_usuario
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_getAllParams())) {
                    $model = new Application_Model_DbTable_Usuarios();
                    /* Traer Datos desde Formulario */
                    $nombre = $form->getValue('nombre');
                    $apellido = $form->getValue('apellido');
                    $telefono = $form->getValue('telefono');
                    $domicilio = $form->getValue('domicilio');
                    $nueva_imagen = $form->getValue('element');

                    if ($nueva_imagen != NULL) {
                        $upload = new Zend_File_Transfer_Adapter_Http();
                        $imagen = strtolower($nueva_imagen);
                        $upload->setDestination(APPLICATION_PATH . '/../public/images/usuarios/');
                        $upload->addFilter('rename', array(
                            'target' => APPLICATION_PATH . '/../public/images/usuarios/' . $imagen,
                            'overwrite' => true
                        ));
                        try {
                            $upload->receive();
                        } catch (Zend_File_Transfer_Exception $e) {
                            $e->getMessage();
                        }
                    } else {
                        $imagen = $form->getValue('foto_perfil');
                    }


                    $model->editarperfil($nombre, $apellido, $telefono, $domicilio, $imagen, $id_usuario);
                    return $this->_redirect('/usuario/');
                }
            }
        } //            
        $form->populate(array('nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono,
            'domicilio' => $domicilio,
            'foto_perfil' => $foto
        ));


        $this->view->form = $form;
    }

    public function hacerreservaAction() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $info = Zend_Auth::getInstance()->getIdentity();
            $model = new Application_Model_DbTable_Usuarios();
            $usser = $model->traerdatoscliente($info);
            $this->view->datosuser = $usser;
            $layout = Zend_Layout::getMvcInstance();
            $view = $layout->getView();
            foreach ($usser as $user) {
                $view->whatever = $user->foto_perfil;
                $view->name = $user->nombre;
            }
            $form = new Application_Form_Hacerreserva();
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_getAllParams())) {
                    $model = new Application_Model_DbTable_Reservas();
                    /* Traer Datos desde Formulario */
                    $personas = $form->getValue('personas');
                    $fecha_reserva = $form->getValue('datepicker');// fecha ingresada 
                    var_dump($fecha_reserva);
                   
                    //Transformacion 
                    $fecha_reserva2= date('Y-m-d H:i:s',strtotime($fecha_reserva));
                  
                    
                    $observacion = $form->getValue('observacion');
                    $idUsuario = $user->id_usuario;
                    $id_estado_reserva = 1;
                    $fechavigencia = new Zend_Db_Expr('NOW()');
                    $model->agregarreserva($personas, $fecha_reserva2, $observacion, $fechavigencia, $idUsuario, $id_estado_reserva);
                    return $this->_redirect('/usuario/');
                }
            }
            $this->view->form = $form;
        } else {
            return $this->_redirect('/index/');
        }
    }

    public function verreservasAction() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $info = Zend_Auth::getInstance()->getIdentity();
            $model = new Application_Model_DbTable_Usuarios();
            $usser = $model->traerdatoscliente($info);
            $this->view->datosuser = $usser;
            $layout = Zend_Layout::getMvcInstance();
            $view = $layout->getView();
            foreach ($usser as $user) {
                $view->whatever = $user->foto_perfil;
                $view->name = $user->nombre;
            }

            $modelo = new Application_Model_DbTable_Reservas();
            $reservas = $modelo->traerreservas($user->id_usuario);
            Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
            $paginator = Zend_Paginator::factory($reservas);
            if ($this->_hasParam('page')) {
                $paginator->setCurrentPageNumber($this->_getParam('page'));
            }
            $this->view->paginator = $paginator;
        }
    }

    public function vercomprasAction() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $info = Zend_Auth::getInstance()->getIdentity();
            $model = new Application_Model_DbTable_Usuarios();
            $usser = $model->traerdatoscliente($info);
            $this->view->datosuser = $usser;
            $layout = Zend_Layout::getMvcInstance();
            $view = $layout->getView();
            foreach ($usser as $user) {
                $view->whatever = $user->foto_perfil;
                $view->name = $user->nombre;
            }
            $modelo = new Application_Model_DbTable_Ventas();
            $ventas = $modelo->ventaporcliente($user->id_usuario);
            Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
            $paginator = Zend_Paginator::factory($ventas);
            if ($this->_hasParam('page')) {
                $paginator->setCurrentPageNumber($this->_getParam('page'));
            }
            $this->view->paginator = $paginator;
        }
    }

}

