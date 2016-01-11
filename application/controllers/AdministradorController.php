<?php

/*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  Title : Administrador Controller
  Author : Josefina - Cristian
  Description :
  Created :
  Modified :
  - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 */

class AdministradorController extends Zend_Controller_Action {

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
            Zend_Registry::set('permiso', $permisoadmin);
            Zend_Registry::set('id_usuario', $id_user);

            if ($tipo_user != 1) {
                return $this->_redirect('/index');
            }
        } else {
            return $this->_redirect('/index');
        }
    }

    public function indexAction() {
        
    }

    public function agregarventaAction() {

        $form = new Application_Form_Agregarventa();
        $this->view->form = $form;
    }

    public function agregarclienteAction() {

        $form = new Application_Form_Agregarcliente();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $nombre = $form->getValue('nombre');
                $apellido = $form->getValue('apellido');
                $email = $form->getValue('email');
                $telefono = '';
                $domicilio = '';
                $nick_login = '';
                $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
                $cad = "";
                for ($i = 0; $i < 8; $i++) {
                    $cad .= substr($str, rand(0, 62), 1);
                }
                //Generar Contraseña Aleatoria y enviarla por email 
                $contraseña = $cad;
                $visibilidad = 1;

                $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'capicua.contacto@gmail.com', 'password' => 'capicuarestobar');
                $smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);

                $mail = new Zend_Mail();
                $mail->setBodyText('Su Clave es: ' . $contraseña);
                $mail->setFrom('capicua.contacto@gmail.com ', 'Administracion Capicua Restobar');
                $mail->addTo($email, $nombre);
                $mail->setSubject('Clave entrada');
                $mail->send($smtpConnection);

                $contraseña2 = md5($contraseña);
                $fotoperfil = 'default_user.png';
                $puntos = 0;
                $tipo_usuario = 2;
                $permiso = 0;
                $fechaalta = new Zend_Db_Expr('NOW()');
                $ultimoacceso = new Zend_Db_Expr('NOW()');
                $model = new Application_Model_DbTable_Usuarios();
                $model->save($nombre, $apellido, $email, $telefono, $domicilio, $fotoperfil, $puntos, $contraseña2, $tipo_usuario, $permiso, $fechaalta, $ultimoacceso, $visibilidad);
                //$model->save($nombre, $apellido, $email, $telefono, $domicilio, $fotoperfil, $puntos, $nick_login, $contraseña2, $tipo_usuario, $permiso, $fechaalta, $ultimoacceso, $visibilidad);
                return $this->_redirect('/administrador');
            }
        }
        $this->view->formuluario = $form;
    }

    public function agregarmenuAction() {
        $form = new Application_Form_Agregarmenu();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $nombre = $form->getValue('nombre');
                $descripcion = $form->getValue('descripcion');
                $precio = $form->getValue('precio');
                $estado_web = 0;
                $fecha = strftime("%Y-%m-%d-%H-%M-%S", time());
                $puntos_producto = $form->getValue('puntos_producto');
                $id_tipo_producto = $form->getValue('id_tipo_producto');
                $nueva_imagen = $form->getValue('element');
                if ($nueva_imagen != NULL) {
                    $name = strtolower($nueva_imagen);
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination(APPLICATION_PATH . '/../public/images/carta/');
                    $upload->addFilter('rename', array(
                        'target' => APPLICATION_PATH . '/../public/images/carta/' . $name,
                        'overwrite' => true
                    ));

                    try {
                        $upload->receive();
                    } catch (Zend_File_Transfer_Exception $e) {
                        $e->getMessage();
                    }
                } else {
                    $name = 'default_carta.jpg';
                }

                $model = new Application_Model_DbTable_CartaProductos();
                $model->agregarproducto($nombre, $descripcion, $precio, $name, $puntos_producto, $estado_web, $id_tipo_producto, $fecha);

                return $this->_redirect('/administrador');
            }
        }
        $this->view->formuluario = $form;
    }

    public function agregarpublicacionAction() {

        $idUsuario = Zend_Registry::get('id_usuario');
        $form = new Application_Form_Agregarpublicidad();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_DbTable_Publicaciones();
                /* Traer Datos desde Formulario */
                $titulo = $form->getValue('titulo');
                $contenido = $form->getValue('contenido');
                $idPublicacion = $form->getValue('tipopublicacion');
                $fecha_publicacion = new Zend_Db_Expr('NOW()');
                $fecha_vigencia = $form->getValue('datepicker'); // cambiar 
                //Transformacion 
                $fecha_vigencia2 = date('Y-m-d H:i:s', strtotime($fecha_vigencia));

                $nueva_foto = $form->getValue('element');


                if ($nueva_foto != NULL) {
                    $foto = strtolower($nueva_foto);
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination(APPLICATION_PATH . '/../public/images/publicaciones/');
                    $upload->addFilter('rename', array(
                        'target' => APPLICATION_PATH . '/../public/images/publicaciones/' . $foto,
                        'overwrite' => true
                    ));
                    try {
                        $upload->receive();
                    } catch (Zend_File_Transfer_Exception $e) {
                        $e->getMessage();
                    }
                } else {
                    $foto = 'default_publicacion.jpg';
                }
                /*                 * ******************************** */
                $model->save($titulo, $contenido, $foto, $fecha_vigencia2, $idUsuario, $idPublicacion, $fecha_publicacion);

//                Posteo hacia Facebook

                /* Datos Facebook
                 * 
                 * mail: capicua.contacto@gmail.com
                 * pass: capicuarestobar
                 */

                $user = Capicua_Facebook::getUser();
                if ($user) {
                    try {
                        $post_url = '/452679718153572/feed';
                        $userMessage = $titulo;
                        $msg_body = array(
                            'message' => $userMessage,
                            'name' => $userMessage,
                            'caption' => 'Capicua Restobar',
                            'description' => $contenido,
                            'picture' => 'http://capicua-restobar.cl/images/publicaciones/' . $foto,
                            'actions' => array(
                                array(
                                    'name' => 'Capicua Restobar',
                                    'link' => 'http://capicua-restobar.cl'
                                )
                            )
                        );
                        $postResult = Capicua_Facebook::api($post_url, 'post', $msg_body);
                    } catch (FacebookApiException $e) {
                        echo $e->getMessage();
                    }

                    if ($postResult) {
                        return $this->_redirect('/publicaciones');
                    }
                }



                return $this->_redirect('/publicaciones');
            }
        }
        $this->view->form = $form;
    }

    public function agregarproductoAction() {
        $form = new Application_Form_Agregarproducto();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_DbTable_CartaProductos();
                $nombre = $form->getValue('nombre');
                $descripcion = $form->getValue('descripcion');
                $precio = $form->getValue('precio');
                $puntos_producto = $form->getValue('puntos_producto');
                $id_tipo_producto = $form->getValue('id_tipo_producto');
                $estado_web = 1;
                $nueva_foto = $form->getValue('element');
                $fecha = new Zend_Db_Expr('NOW()');


                if ($nueva_foto != NULL) {
                    $name = strtolower($nueva_foto);
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination(APPLICATION_PATH . '/../public/images/carta/');
                    $upload->addFilter('rename', array(
                        'target' => APPLICATION_PATH . '/../public/images/carta/' . $name,
                        'overwrite' => true
                    ));
                    try {
                        $upload->receive();
                    } catch (Zend_File_Transfer_Exception $e) {
                        $e->getMessage();
                    }
                } else {
                    $name = 'default_carta.jpg';
                }

                $model->agregarproducto($nombre, $descripcion, $precio, $name, $puntos_producto, $estado_web, $id_tipo_producto, $fecha);
                return $this->_redirect('/administrador/listarproductos');
            }
        }

        $this->view->form = $form;
    }

    public function editarpublicacionesAction() {
        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administrador/listarpublicaciones');
        }
        $form = new Application_Form_Editarpublicacion();
        $publi = new Application_Model_DbTable_Publicaciones();

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_DbTable_Publicaciones();
                /* Traer Datos desde Formulario */
                $titulo = $form->getValue('titulo');
                $contenido = $form->getValue('contenido');
                $picker = $form->getValue('datepicker');
                $tipo = $form->getValue('tipopublicacion');
               
                   

                if ($picker != "") { // si al editar ingresan nueva fecha de vigencia
                    $fecha_vigencia = $form->getValue('datepicker'); // todo lo que sea datapiker cambiado !
                    $fecha_vigencia2 = date('Y-m-d H:i:s', strtotime($fecha_vigencia));
                  
                } else { // no fecha
                    $fecha_vigencia2 = $form->getValue('fecha_vigencia');
                    
                }
                $nueva_imagen = $form->getValue('element');
                if ($nueva_imagen != NULL) {
                    $imagen = strtolower($nueva_imagen);
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination(APPLICATION_PATH . '/../public/images/publicaciones/');
                    $upload->addFilter('rename', array(
                        'target' => APPLICATION_PATH . '/../public/images/publicaciones/' . $imagen,
                        'overwrite' => true
                    ));


                    try {
                        $upload->receive();
                    } catch (Zend_File_Transfer_Exception $e) {
                        $e->getMessage();
                    }
                } else {
                    $imagen = $form->getValue('imagen');
                }

                $model->editarPublicidad($titulo, $contenido, $imagen, $fecha_vigencia2, $tipo, $this->_getParam('id'));
                return $this->_redirect('/administrador/listarpublicaciones/');
            }
        } else {

            $row = $publi->obtenerRow($this->_getParam('id'));
            if ($row) {
                $form->populate($row->toArray());
            }
        }

        $this->view->form = $form;
    }

    public function listarpublicacionesAction() {

        $model = new Application_Model_DbTable_Publicaciones();
        $posts = $model->listar_todos();

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($posts);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
    }

    public function borrarpublicacionesAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administrador/listarpublicaciones');
        }
        $model = new Application_Model_DbTable_Publicaciones();
        $row = $model->obtenerRow($this->_getParam('id'));

        if ($row) {
            $row->delete();
        }
        return $this->_redirect('/administrador/listarpublicaciones');
    }

    public function listarmenusAction() {

        $model = new Application_Model_DbTable_CartaProductos();
        $posts = $model->getAll();

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($posts);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
    }

    public function editarmenusAction() {
        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administrador/listarmenus');
        }
        $form = new Application_Form_Agregarmenu();
        $publi = new Application_Model_DbTable_CartaProductos();

        if ($this->getRequest()->isPost()) {


            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_DbTable_CartaProductos();

                /* Traer Datos desde Formulario */
                $nombre = $form->getValue('nombre');
                $descripcion = $form->getValue('descripcion');
                $precio = $form->getValue('precio');
                $puntos = $form->getValue('puntos_producto');
                $tipo = $form->getValue('id_tipo_producto');
                $nueva_imagen = $form->getValue('element');

                if ($nueva_imagen != NULL) {
                    $foto = strtolower($nueva_imagen);
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination(APPLICATION_PATH . '/../public/images/carta/');
                    $upload->addFilter('rename', array(
                        'target' => APPLICATION_PATH . '/../public/images/carta/' . $foto,
                        'overwrite' => true
                    ));
                    try {
                        $upload->receive();
                    } catch (Zend_File_Transfer_Exception $e) {
                        $e->getMessage();
                    }
                } else {

                    $foto = $form->getValue('imagen');
                }
                /*                 * ******************************** */

                $model->editarmenus($nombre, $descripcion, $precio, $puntos, $tipo, $foto, $this->_getParam('id'));
                return $this->_redirect('/administrador/listarmenus/');
            }
        } else {

            $row = $publi->obtenerRow($this->_getParam('id'));
            if ($row) {
                $form->populate($row->toArray());
            }
        }

        $this->view->form = $form;
    }

    public function borrarmenusAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administrador/listarmenus');
        }
        $model = new Application_Model_DbTable_CartaProductos();
        $row = $model->obtenerRow($this->_getParam('id'));

        if ($row) {
            $row->delete();
        }
        return $this->_redirect('/administrador/listarmenus');
    }

    public function listarproductosAction() {

        $model = new Application_Model_DbTable_CartaProductos();
        $posts = $model->getproductos();

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($posts);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
    }

    public function cambiarvisibilidadAction() {
        if (!$this->_getParam('id')) {
            return $this->_redirect('/administrador/listarproductos');
        }

        $modeluser = new Application_Model_DbTable_Usuarios();
        $visibilidad = $modeluser->vervisibilidad($this->_getParam('id'));
        $cambiovisible = 0;
        if ($visibilidad == 1) { //Visible
            $cambiovisible = 0;
        } else {
            $cambiovisible = 1;
        }

        $modeluser->editarvisibilidad($this->_getParam('id'), $cambiovisible);
        return $this->_redirect('/administrador/listarclientes');
    }

    public function editarproductosAction() {
        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administrador/listarproductos');
        }
        $form = new Application_Form_Agregarproducto();
        $publi = new Application_Model_DbTable_CartaProductos();

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_DbTable_CartaProductos();

                /* Traer Datos desde Formulario */
                $nombre = $form->getValue('nombre');
                $descripcion = $form->getValue('descripcion');
                $tipo = $form->getValue('id_tipo_producto');
                $precio = $form->getValue('precio');
                $puntos = $form->getValue('puntos_producto');
                $nueva_imagen = $form->getValue('element');
                if ($nueva_imagen != NULL) {
                    $imagen = strtolower($nueva_imagen);
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination(APPLICATION_PATH . '/../public/images/carta/');
                    $upload->addFilter('rename', array(
                        'target' => APPLICATION_PATH . '/../public/images/carta/' . $imagen,
                        'overwrite' => true
                    ));
                    try {
                        $upload->receive();
                    } catch (Zend_File_Transfer_Exception $e) {
                        $e->getMessage();
                    }
                } else {
                    $imagen = $form->getValue('imagen');
                }

                $model->editarproductos($nombre, $descripcion, $tipo, $precio, $puntos, $imagen, $this->_getParam('id'));
                // echo '<script type="text/javascript">alert("El Producto : ' . $nombre . 'a sido editado");</script>';
                return $this->_redirect('/administrador/listarproductos/');
            }
        } else {

            $row = $publi->obtenerRow($this->_getParam('id'));
            if ($row) {
                $form->populate($row->toArray());
            }
        }

        $this->view->form = $form;
    }

    public function borrarproductosAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administrador/listarproductos');
        }
        $model = new Application_Model_DbTable_CartaProductos();
        $row = $model->obtenerRow($this->_getParam('id'));
        if ($row) {

            //echo '<script type="text/javascript">alert("El Producto : ' . $row->nombre . 'a sido Borrado");</script>';

            $row->delete();
        }
        return $this->_redirect('/administrador/listarproductos');
    }

    public function confirmarventaAction() {

        $form = new Application_Form_Buscarcliente();
        //recibe la lista de productos
        $listaproductos = $this->_getParam("products_selected");
        $model = new Application_Model_DbTable_CartaProductos();
        $puntos1 = $form->getValue('puntos');
        $productosbase = $model->getproductos();

        if ($this->getRequest()->isPost()) {
            //Calculo subtotal

            if ($form->isValid($this->_getAllParams())) {

                $sub_total = 0;
                $puntosventa = 0;
                foreach ($productosbase as $base) {
                    foreach ($listaproductos as $product) {
                        $chunks = explode('|', $product);
                        $product_id = $chunks[0];
                        if ($base->id_producto == $product_id) {
                            $product_qty = $chunks[1];
                            $product_price = $base->precio;
                            $puntosventa = $puntosventa + ($base->puntos_producto * $product_qty);
                            $product_amount = $product_price * $product_qty;
                            $sub_total = $sub_total + $product_amount;
                        }
                    }
                }
                $fecha = new Zend_Db_Expr('NOW()');
                $id_usuario = $form->getvalue('cliente');
                $observacion = $form->getvalue('observacion');
                $mesa = $form->getvalue('mesa');
                $puntoscliente = $form->getvalue('puntos');
                $descuento = $form->getvalue('descuento');

                if ($puntoscliente >= 1000 && $descuento <= $puntoscliente && $sub_total >= $descuento) {
                    $descuento = $descuento;
                    //Descuento de puntos y venta
                    $sub_total = $sub_total - $descuento;
                    $puntoscliente = $puntoscliente - $descuento;
                    $puntosclientefinal = $puntoscliente + $puntosventa;
                    $modeluser = new Application_Model_DbTable_Usuarios();
                    $modeluser->editarpuntosacumulados($puntosclientefinal, $id_usuario);
                } else {
                    $descuento = 0;
                    $sub_total = $sub_total;
                    $puntosclientefinal = $puntoscliente + $puntosventa;
                    $modeluser = new Application_Model_DbTable_Usuarios();
                    $modeluser->editarpuntosacumulados($puntosclientefinal, $id_usuario);
                }

                $model = new Application_Model_DbTable_Ventas();
                //Agrego venta * validar
                $idventa = $model->crearventa($sub_total, $mesa, $fecha, $puntosventa, $descuento, $observacion, $id_usuario);

                //Integracion de productos a la tabla relacional ventas-productos
                foreach ($productosbase as $base) {
                    foreach ($listaproductos as $product) {
                        $chunks = explode('|', $product);
                        $product_id = $chunks[0];
                        if ($base->id_producto == $product_id) {
                            $product_qty = $chunks[1];
                            $modelorelacion = new Application_Model_DbTable_RelCartaVentas();
                            $modelorelacion->agregarProductosVenta($idventa, $product_id, $product_qty);
                        }
                    }
                }
                return $this->_redirect('/administrador');
            }
        }

        $this->view->element = $form;
        $this->view->punto = $puntos1;
        $this->view->productobase = $productosbase;
        $this->view->listaventa = $listaproductos;
    }

    public function getmodelosAction() {
        $modelModelo = new Application_Model_DbTable_Usuarios();
        $results = $modelModelo->getAsKeyValueJSON($this->_getParam('cliente'));
        $this->_helper->json($results);
    }

    public function listartipoproductosAction() {
        $permiso = Zend_Registry::get('permiso');

        if ($permiso == 1) {
            $model = new Application_Model_DbTable_TipoProductos();
            $posts = $model->traertodo();

            Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
            $paginator = Zend_Paginator::factory($posts);

            if ($this->_hasParam('page')) {
                $paginator->setCurrentPageNumber($this->_getParam('page'));
            }

            $this->view->paginator = $paginator;
        } else {
            echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
        }
    }

    /*
     * Summary:      Edita Tipo Producto
     *               
     * Parameters:   $permiso => Tipo Global
     *               $this->hasParam('id') => id_tipo_producto 
     * Return:       Retorna a la pagina de Listar Tipo Producto
     */

    public function editartipoproductosAction() {

        $permiso = Zend_Registry::get('permiso');
        if ($permiso == 1) {
            if (!$this->_hasParam('id')) {
                return $this->_redirect('/administrador/listartipoproductos');
            }
            $form = new Application_Form_Editartipo();
            $publi = new Application_Model_DbTable_TipoProductos();
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_getAllParams())) {
                    $model = new Application_Model_DbTable_TipoProductos();
                    /* Traer Datos desde Formulario */
                    $nombre = $form->getValue('nombre_tipo');
                    $tipo = $form->getValue('tipo_menu');
                    $model->editaruntipo($nombre, $tipo, $this->_getParam('id'));
                    return $this->_redirect('/administrador/listartipoproductos/');
                }
            } else {
                $row = $publi->traeruntipo($this->_getParam('id'));
                foreach ($row as $datotipo):
                    $nombre = $datotipo->nombre_tipo;
                    $tipo_menu = $datotipo->tipo_menu;
                endforeach;
                $form->populate(array('nombre_tipo' => $nombre,
                    'tipo_menu' => $tipo_menu,
                ));
            }
            $this->view->form = $form;
        } else {

            echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
        }
    }

    /*
     * Summary:      Agrega Tipo Producto             
     * Parameters:   $permiso / Tipo Global
     * Return:       Retorna a la pagina de Listar Tipo Producto
     */

    public function agregartipoproductoAction() {
        $permiso = Zend_Registry::get('permiso');
        if ($permiso == 1) {
            $form = new Application_Form_Editartipo();
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_getAllParams())) {
                    $model = new Application_Model_DbTable_TipoProductos();
                    $nombre = $form->getValue('nombre_tipo');
                    $tipo_menu = $form->getValue('tipo_menu');
                    $visibilidad = 1;
                    $model->agregaruntipo($nombre, $tipo_menu, $visibilidad);
                    return $this->_redirect('/administrador/listartipoproductos');
                }
            }
            $this->view->form = $form;
        } else {

            echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
        }
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

    public function verreservasAction() {

        $form = new Application_Model_DbTable_Reservas();
        $reservas = $form->mostrarreservas();

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($reservas);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }
        $this->view->paginator = $paginator;
    }

    public function editarreservaAction() {

        $id_reserva = $this->getParam('id_reserva'); // recibo de formulario
        if ($id_reserva) {
            $reserva = $id_reserva;
        } else {//primero entra aca
            if (!$this->_getParam('id')) {

                return $this->_redirect('/administrador/verreservas');
            } else {// editarreserva/ID/1
                $reserva = $this->_getParam('id');
            }
        }
        $formulario = new Application_Form_Editarreserva();
        if ($this->getRequest()->isPost()) {
            if ($formulario->isValid($this->_getAllParams())) {
                $modelo = new Application_Model_DbTable_Reservas();
                /* Traer Datos desde Formulario */
                $estado = $formulario->getValue('tipoestado');
                $email = $formulario->getValue('email');
                $nombre_cliente = $formulario->getValue('nombre_cliente');
                $fecha_reserva = $formulario->getValue('fecha_reserva');

                //Conversion Estado:
                if ($estado == 1) {
                    $nombreestado = 'Pendiente';
                    $msn='Su Reserva Se encuentra en Espera, le rogamos pueda ser paciente, le comunicaremos lo antes posible su confirmacion';
                }
                if ($estado == 2) {
                    $nombreestado = 'Aprobada'; // si es aprobada
                    $msn= 'Se Ruego Confirmar el Horario a Reservar, por este mismo medio.';
                }
                if ($estado == 3) {
                    $nombreestado = 'Rechazada';
                    $msn= 'Su reserva Fue rechazada, debido a una sobredemanda para ese Dia ';
                    
                }

                /* Conversion de Fecha */
                $date = $fecha_reserva;
                $dia = explode("-", $date, 3);
                $year = $dia[0];
                $month = (string) (int) $dia[1];
                $day = (string) (int) $dia[2];
                $dias = array("domingo", "lunes", "martes", "mi&eacute;rcoles", "jueves", "viernes", "s&aacute;bado");
                $tomadia = $dias[intval((date("w", mktime(0, 0, 0, $month, $day, $year))))];
                $meses = array("", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
                $dia_reserva = $tomadia . ", " . $day . " de " . $meses[$month] . " de " . $year;
                /*  FIN Conversion de Fecha */

                //Envio de Email  para dar a conocer el cambio de estado de la reserva
                $asunto = 'Estado de su reserva en Capicua Restobar';
                $mensaje = 'Estimado(a): ' . $nombre_cliente . ' </br> La Reserva para el Dia:' . $dia_reserva . ' fue ' . $nombreestado . '.'.'</br>'.$msn;
                $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'capicua.contacto@gmail.com', 'password' => 'capicuarestobar');
                $smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);

                $mail = new Zend_Mail('utf-8');
                $mail->setSubject($asunto);
                $mail->setFrom('capicua.contacto@gmail.com', 'Administracion Capicua Restobar');
                $mail->addTo($email);
                $mail->setBodyHtml($mensaje);
                $mail->send($smtpConnection);

                $modelo->cambiarestadoreserva($estado, $reserva);
                return $this->_redirect('/administrador/verreservas/');
            }
        }
        $modelo = new Application_Model_DbTable_Reservas();
        $datosreserva = $modelo->mostrarunareserva($reserva);
        foreach ($datosreserva as $datos) {
            $nombre = $datos->nombre;
            $personas = $datos->cantidad_personas;
            $email = $datos->email;
            $observacion = $datos->observacion;
            $fecha_reserva = $datos->fecha_reserva;
            $fecha_realizacion = $datos->fecha_realizacion;
            $id_estado_reserva = $datos->id_estado_reserva;
        }

        $formulario->populate(array('nombre_cliente' => $nombre,
            'personas' => $personas,
            'observacion' => $observacion,
            'email' => $email,
            'fecha_reserva' => $fecha_reserva,
            'fecha_realizacion' => $fecha_realizacion,
            'tipoestado' => $id_estado_reserva
        ));
        $formulario->getElement('id_reserva')->setValue($reserva);

        $this->view->form = $formulario;
    }

    public function envioemailmasivoAction() {


        $permiso = Zend_Registry::get('permiso');

        if ($permiso == 1) {
            $form = new Application_Form_Emailmasivo();
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_getAllParams())) {

                    $html = new Zend_View();
                    $html->setScriptPath(APPLICATION_PATH . '/views/scripts/emails/');

                    $model = new Application_Model_DbTable_Usuarios();
                    $datosusuarios = $model->traerdatosclientes();
                    $asunto = $form->getValue('asunto');
                    $mensaje = $form->getValue('mensaje');
                    $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'capicua.contacto@gmail.com', 'password' => 'capicuarestobar');
                    $smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
                    $mail = new Zend_Mail('utf-8');
                    $mail->setSubject($asunto);
                    $mail->setFrom('capicua.contacto@gmail.com', 'Administracion Capicua Restobar');
                    $html->assign('mensaje', $mensaje);
                    foreach ($datosusuarios as $subscriber) {
                        $mail->addTo($subscriber->email, $subscriber->nombre);
                    }
                    $bodyText = $html->render('template.phtml');
                    $mail->setBodyHtml($bodyText);
                    $mail->send($smtpConnection);
                    return $this->_redirect('/administrador');
                }
            }
            $this->view->form = $form;
        } else {

            echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
        }
    }

    public function listarclientesAction() {

        $modelo = new Application_Model_DbTable_Usuarios();
        $clientes = $modelo->todoslosclientes();

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($clientes);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }
        $this->view->paginator = $paginator;
    }

    public function listaradministradoresAction() {
        $permiso = Zend_Registry::get('permiso');

        if ($permiso == 1) {
            $modelo = new Application_Model_DbTable_Usuarios();
            $admins = $modelo->todoslosadministradores();
            Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
            $paginator = Zend_Paginator::factory($admins);

            if ($this->_hasParam('page')) {
                $paginator->setCurrentPageNumber($this->_getParam('page'));
            }
            $this->view->paginator = $paginator;
        } else {


            echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
        }
    }

    public function agregaradministradorAction() {

        $permiso = Zend_Registry::get('permiso');

        if ($permiso == 1) {
            $form = new Application_Form_Agregaradministrador();
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_getAllParams())) {
                    $model = new Application_Model_DbTable_Usuarios();
                    $nombre = $form->getValue('nombre');
                    $apellido = $form->getValue('apellido');
                    $email = $form->getValue('email');
                    $password = $form->getValue('password');
                    $tipo_usuario = 1;
                    $permiso = $form->getValue('permiso');
                    $fechaalta = new Zend_Db_Expr('NOW()');
                    $password2 = md5($password);
                    //Visibilidad 1= VISIBLE  y 0 = NO VISIBLE
                    $visibilidad = 1;
                    if ($permiso == 1) {
                        $foto = 'super_admin_default.png';
                    } else {
                        $foto = 'fotoadmin.png';
                    }

                    $model->agregaradministrador($nombre, $apellido, $email, $password2, $tipo_usuario, $permiso, $fechaalta, $foto, $visibilidad);
                    return $this->_redirect('/administrador/listaradministradores');
                }
            }
            $this->view->form = $form;
        } else {

            echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
        }
    }

    public function agregartipopublicacionAction() {
        $permiso = Zend_Registry::get('permiso');
        if ($permiso == 1) {
            $form = new Application_Form_Agregartipopublicacion();

            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_getAllParams())) {
                    $model = new Application_Model_DbTable_TipoPublicaciones();
                    $nombre = $form->getValue('nombre_tipo');


                    $model->agregartipo($nombre);
                    return $this->_redirect('/administrador');
                }
            }

            $this->view->form = $form;
        } else {

            echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
        }
    }

    public function verventasAction() {


        $model = new Application_Model_DbTable_Ventas();
        $posts = $model->verventas();

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($posts);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
    }

    public function borrarventaAction() {


        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administrador/verventas');
        }
        $model = new Application_Model_DbTable_Ventas();
        $row = $model->obtenerRow($this->_getParam('id'));

        if ($row) {
            $row->delete();
        }
        return $this->_redirect('/administrador/verventas');
    }

    public function ventaclienteAction() {

        $formulario = new Application_Form_Clientebusca();
        $this->view->form = $formulario;
    }

    public function editarvisibilidadtipoproductoAction() {
        if (!$this->_getParam('id')) {
            return $this->_redirect('/administrador/listartipoproductos');
        }

        $modeluser = new Application_Model_DbTable_TipoProductos();
        $visibilidad = $modeluser->vervisibilidad($this->_getParam('id'));
        $cambiovisible = 0;
        if ($visibilidad == 1) { //Visible
            $cambiovisible = 0;
        } else {
            $cambiovisible = 1;
        }

        $modeluser->editarvisibilidad($this->_getParam('id'), $cambiovisible);
        return $this->_redirect('/administrador/listartipoproductos');
    }

    public function listartipopublicacionesAction() {

        $permiso = Zend_Registry::get('permiso');
        if ($permiso == 1) {
            $model = new Application_Model_DbTable_TipoPublicaciones();
            $tipo = $model->traertipoproductos();
            Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
            $paginator = Zend_Paginator::factory($tipo);
            if ($this->_hasParam('page')) {
                $paginator->setCurrentPageNumber($this->_getParam('page'));
            }
            $this->view->paginator = $paginator;
        } else {

            echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
        }
    }

    public function editarvisibilidadtipopublicacionAction() {
        if (!$this->_getParam('id')) {
            return $this->_redirect('/administrador/listartipopublicaciones');
        }

        $modeluser = new Application_Model_DbTable_TipoPublicaciones();
        $visibilidad = $modeluser->vervisibilidad($this->_getParam('id'));
        $cambiovisible = 0;
        if ($visibilidad == 1) { //Visible
            $cambiovisible = 0;
        } else {
            $cambiovisible = 1;
        }

        $modeluser->editarvisibilidad($this->_getParam('id'), $cambiovisible);
        return $this->_redirect('/administrador/listartipopublicaciones');
    }

    public function clienteventaAction() {
        if ($this->getRequest()->isPost()) {
            $cliente = $this->getParam('cliente');
            return $this->_redirect('/administrador/ventaporcliente/id/' . $cliente);
        }
    }

    public function ventaporclienteAction() {


        $cliente = $this->_getParam('id');

        $modelventa = new Application_Model_DbTable_Ventas();

        $datos = $modelventa->ventaporcliente($cliente);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($datos);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
    }

    public function editartipopublicacionAction() {

        $permiso = Zend_Registry::get('permiso');
        if ($permiso == 1) {
            if (!$this->_hasParam('id')) {
                return $this->_redirect('/administrador/listartipoproductos');
            }
            $form = new Application_Form_Agregartipopublicacion();
            $publi = new Application_Model_DbTable_TipoPublicaciones();
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_getAllParams())) {
                    $model = new Application_Model_DbTable_TipoPublicaciones();
                    /* Traer Datos desde Formulario */
                    $nombre = $form->getValue('nombre_tipo');


                    $model->editartipopublicacion($this->_getParam('id'), $nombre);
                    return $this->_redirect('/administrador/listartipopublicaciones/');
                }
            } else {
                $row = $publi->traeruntipopublicacion($this->_getParam('id'));

                foreach ($row as $datotipo):
                    $nombre = $datotipo->nombre_tipo;
                endforeach;
                $form->populate(array('nombre_tipo' => $nombre
                ));
            }
            $this->view->form = $form;
        } else {
            echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
        }
    }

}
