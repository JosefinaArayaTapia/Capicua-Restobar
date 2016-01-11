<?php

require_once("dompdf/dompdf_config.inc.php");


/* ----------------------------------------------------------/* 

  $path     : nombre y/o ruta del pdf (sin la extensión)
  p.e: --> 'ejemplo' , 'pdfs/nuevo-ejemplo'
  si se deja vacío --> se genera uno aleatorio

  $content  : contenido del pdf

  $body     : true o false.
  true  --> Añade; <doctype>, <body>, <head> a $content
  false --> no altera el $content

  $style    : la ruta de la CSS. Puede estar vacía
  Para cargar una css --> necesita $body = true;

  $mode     : true o false.
  true  --> guarda el pdf en un directorio y lo muestra
  false --> pregunta si guarda o abre el archivo

  $paper_1  : tamaño del papel[*]
  $paper_2  : estilo del papel[*]

  [*] como ver las opciones disponibles:
  --> http://code.google.com/p/dompdf/wiki/Usage#Invoking_dompdf_via_the_command_line

  /*---------------------------------------------------------- */

class ReportesController extends Zend_Controller_Action {

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
                $view->ultimo = $user->ultimo_acceso;
                $view->permiso = $permisoadmin;
            }
            Zend_Registry::set('permiso', $permisoadmin);
            Zend_Registry::set('id_usuario', $id_user);

            if ($tipo_user != 1) {
                return $this->_redirect('/index');
            } else {
                if ($permisoadmin != 1) {
                    echo ('<script type="text/javascript"> 
                alert("Opcion valida solo para SuperAdminstrador "); 
                document.location="/administrador";
            </script> 
            ');
                }
            }
        } else {
            return $this->_redirect('/index');
        }
    }

    public function indexAction() {
        $form = new Application_Form_Filtros();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                /* 1.- Ventas por cliente
                 * 2.- Usuario mas Activo
                 * 3.- 10 productos mas vendidos
                 * 4.- 10 Productos menos vendidos
                 * 5.- Publicaciones mas comentadas
                 */
                $tipo_reporte = $form->getValue('tipo');
                if ($form->getValue('cliente')) {
                    $cliente = $form->getValue('cliente');
                }
                $fecha_inicio = $form->getValue('from');

                $fecha_fin = $form->getValue('to');


                if ($tipo_reporte == 1) {
                    return $this->_redirect('/reportes/ventasporcliente/id/' . $cliente . '/from/' . $fecha_inicio . '/to/' . $fecha_fin);
                }
                if ($tipo_reporte == 2) {
                    return $this->_redirect('/reportes/clientemasactivo/from/' . $fecha_inicio . '/to/' . $fecha_fin);
                }
                if ($tipo_reporte == 3) {
                    return $this->_redirect('/reportes/diezproductosmasvendidos/from/' . $fecha_inicio . '/to/' . $fecha_fin);
                }
                if ($tipo_reporte == 4) {
                    return $this->_redirect('/reportes/diezproductosmenosvendidos/from/' . $fecha_inicio . '/to/' . $fecha_fin);
                }
                if ($tipo_reporte == 5) {
                    return $this->_redirect('/reportes/publicacionesmascomentadas/from/' . $fecha_inicio . '/to/' . $fecha_fin);
                }
                if ($tipo_reporte == 6) {
                    return $this->_redirect('/reportes/clientemasactivopublica/from/' . $fecha_inicio . '/to/' . $fecha_fin);
                }
            }
        }

        $this->view->form = $form;
    }

    public function ventasporclienteAction() {


        $id_cliente = $this->_getParam('id');
        $from = $this->_getParam('from'); // DESDE
        $to = $this->_getParam('to'); // HASTA
//        
//        var_dump($to,$from);
//        exit;

        $model = new Application_Model_DbTable_Ventas();
        $posts = $model->reporte_venta_cliente($id_cliente, $from, $to);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($posts);
        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
        $this->view->id_cliente = $id_cliente;
        $this->view->from = $from;
        $this->view->to = $to;
    }

    public function ventasclienterepoAction() {

        $id_cliente = $this->_getParam('id');
        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $idUsuario = Zend_Registry::get('id_usuario');

        $modelUser = new Application_Model_DbTable_Usuarios();
        $datosAdmin = $modelUser->traerdatosclienteID($idUsuario);
        foreach ($datosAdmin as $admin) {
            $nombreAdmin = $admin->nombre;
            $apellidoAdmin = $admin->apellido;
        }
        $model = new Application_Model_DbTable_Ventas();
        $posts = $model->reporte_venta_cliente($id_cliente, $from, $to);
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();

        $tipo_reporte = 'Reporte Ventas por Usuario';
        $content = '<html>
                     <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>Reportes - Capicua Restobar.</title>
                    </head>
                       <body>' .
                '<img width=100% height=50px src="images/top_footer.jpg"/>
                       <br/><br/> 
                        <h3><center>' . $tipo_reporte . '</center></h3>' . '<br/>
                        <strong><u>Administrador : </u></strong>' . '        ' . $nombreAdmin . '        ' . $apellidoAdmin . '<br/>
                        <strong><u>Fecha : </u></strong>' . '          ' . date("d/m/Y H:i") . '<br/>
                        <div>';

        if ($from || $to) :
            $content = $content . ' <hr>
                                        <li>
                                           <p>Desde: ' . $from . '</p>
                                       </li> 
                                        <li>
                                           <p>Hasta: ' . $to . '</p>
                                       </li>';

        else :

            $content = $content . '     <h3> Reporte Historico</h3>';
        endif;


        $content = $content . '<table cellspacing="10%" cellpadding="10%" width=100%>
                                <tr align = center  BGCOLOR="ccff66">
                                  <td width="2%"><font><u>Nombre</u></font></td>
                    <td width="2%"><font><u>Fecha</u></font></td>
                    <td width="2%"><font><u>Descuento Hecho</u></font></td>
                    <td width="2%"><font><u>Puntos Venta</u></font></td>
                    <td width="2%"><font><u>Total Venta</u></font></td>
                    <td width="2%"><font><u>Cantidad</u></font></td>
                    <td width="2%"><font><u>Producto</u></font></td>
                    <td width="2%"><font><u>Precio Unitario</u></font></td>
                            </tr>';
        $id_prueba = 0;
        $fecha = 0;
        $total = 0;

        if (count($posts)):
            foreach ($posts as $post):
                $content = $content . '<tr align="center" bgcolor="ffff99">';
                if ($post->id_usuario != $id_prueba):
                    $id_prueba = $post->id_usuario;
                    $content = $content . '<td>' . $post->cliente . ' ' . $post->apellido . '</td>';
                else:
                    $content = $content . '<td></td>';
                endif;

                if ($post->fecha != $fecha):
                    $date = $post->fecha;
                    $dia = explode("-", $date, 3);
                    $year = $dia[0];
                    $month = (string) (int) $dia[1];
                    $month2 = str_pad((int) $month, 2, "0", STR_PAD_LEFT);
                    $day = (string) (int) $dia[2];
                    $day2 = str_pad((int) $day, 2, "0", STR_PAD_LEFT);
                    $time = explode(":", $date, 3);
                    $hora = explode(" ", $time[0], 2);
                    $min = (string) (int) $time [1];
                    $min2 = str_pad((int) $min, 2, "0", STR_PAD_LEFT);
                    $fecha = $post->fecha; // Verificacion de no Repeticion

                    $content = $content . '<td>' . $day2 . " -" . $month2 . " - " . $year . " " . "/" . " " . $hora[1] . ":" . $min2 . '</td>';
                else:
                    $content = $content . '<td></td>';
                endif;

                if ($post->total != $total):
                    $total = $post->total;
                    $content = $content . '<td>' . $post->descuento . '</td>';
                    $content = $content . '<td>' . $post->puntos_venta . '</td>';
                    $content = $content . '<td>' . $post->total . '</td>';
                else:
                    
                    $content = $content . '<td></td>';
                    $content = $content . '<td></td>';
                    $content = $content . '<td></td>';
                endif;
                $content = $content . '<td>' . $post->cantidad_producto . '</td>';
                $content = $content . '<td>' . $post->nombre . '</td>';
                $content = $content . '<td>' . $post->precio . '</td>';
                $content = $content . '</tr>';


            endforeach;
        else :

            $content = $content . ' <tr>
    <td colspan="3">No hay datos</td>
</tr>';
        endif;

        $content = $content . '</table><div></body></html>';

        if ($content != '') {
//Añadimos la extensión del archivo. Si está vacío el nombre lo creamos 
            $path = 'Reporte-Ventas_' . date("d/m/Y H:i") . '.pdf';

//Las opciones del papel del PDF. Si no existen se asignan las siguientes:[*] 

            $paper_1 = 'a4';
            $paper_2 = 'portrait';

            $dompdf = new DOMPDF();
            $dompdf->set_paper($paper_1, $paper_2);
            $dompdf->load_html(utf8_encode($content));
//ini_set("memory_limit","32M"); //opcional  
            $dompdf->render();
            $mode = false;
//Creamos el pdf 
            if ($mode == false)
                $dompdf->stream($path);

//Lo guardamos en un directorio y lo mostramos 
            if ($mode == true)
                if (file_put_contents($path, $dompdf->output()))
                    header('Location: ' . $path);
        }
    }

    public function clientemasactivoAction() {

        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $model = new Application_Model_DbTable_Ventas();
        $cliente = $model->reporte_cliente_mas_activo_venta($from, $to);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($cliente);
        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
        $this->view->from = $from;
        $this->view->to = $to;
    }

    public function clientesmasactivosventarepoAction() {
        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $idUsuario = Zend_Registry::get('id_usuario');

        $modelUser = new Application_Model_DbTable_Usuarios();
        $datosAdmin = $modelUser->traerdatosclienteID($idUsuario);
        foreach ($datosAdmin as $admin) {
            $nombreAdmin = $admin->nombre;
            $apellidoAdmin = $admin->apellido;
        }

        $model = new Application_Model_DbTable_Ventas();
        $posts = $model->reporte_cliente_mas_activo_venta($from, $to);
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();


        $tipo_reporte = 'Reporte Clientes Mas Activos (Ventas)';
        $content = '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Reportes - Capicua Restobar.</title>
    </head>
    <body>' .
                '<img width=100% height=50px src="images/top_footer.jpg"/>
        <br/><br/> 
        <h3><center>' . $tipo_reporte . '</center></h3>' . '<br/>
        <strong><u>Administrador : </u></strong>' . '        ' . $nombreAdmin . '        ' . $apellidoAdmin . '<br/>
        <strong><u>Fecha : </u></strong>' . '          ' . date("d/m/Y H:i") . '<br/>';
        if ($from || $to) :
            $content = $content . ' <hr>
                                        <li>
                                           <p>Desde: ' . $from . '</p>
                                       </li> 
                                        <li>
                                           <p>Hasta: ' . $to . '</p>
                                       </li>';

        else :

            $content = $content . '     <h3> Reporte Historico</h3>';
        endif;

        $content = $content . ' <div>
            <table cellspacing="10%" cellpadding="10%" width=100%>
                <tr align = center  BGCOLOR="ccff66">
                    <td width="4%"><font><u>Nombre</u></font></td>
                <td width="4%"><font><u>Email</u></font></td>
                <td width="2%"><font><u>Total de Ventas</u></font></td>

                </tr>';

        if (count($posts)) {
            foreach ($posts as $tipo) :
                $content = $content . '<tr align="center" bgcolor="ffff99">';
                if ($tipo) {
                    $content = $content . '<td>' . $tipo->nombre . ' ' . $tipo->apellido . '</td>';
                    $content = $content . '<td>' . $tipo->email . '</td>';
                    $content = $content . '<td>' . $tipo->num . '</td>';
                    $content = $content . '</tr>';
                }
            endforeach;
        } else {
            $content = $content . ' <tr>
                    <td colspan="3">No hay datos</td>
                </tr>';
        }
        $content = $content . '</table><div></body></html>';

        if ($content != '') {
            //Añadimos la extensión del archivo. Si está vacío el nombre lo creamos 
            $path = 'Reporte clientes mas activos - ventas' . date("d/m/Y H:i") . '.pdf';

            //Las opciones del papel del PDF. Si no existen se asignan las siguientes:[*] 

            $paper_1 = 'a4';
            $paper_2 = 'portrait';

            $dompdf = new DOMPDF();
            $dompdf->set_paper($paper_1, $paper_2);
            $dompdf->load_html(utf8_encode($content));
            //ini_set("memory_limit","32M"); //opcional  
            $dompdf->render();
            $mode = false;
            //Creamos el pdf 
            if ($mode == false)
                $dompdf->stream($path);

            //Lo guardamos en un directorio y lo mostramos 
            if ($mode == true)
                if (file_put_contents($path, $dompdf->output()))
                    header('Location: ' . $path);
        }
    }

    public function clientemasactivopublicaAction() {


        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $model = new Application_Model_DbTable_Comentarios();
        $cliente = $model->reporte_cliente_mas_activo_publica($from, $to);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($cliente);
        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
        $this->view->from = $from;
        $this->view->to = $to;
    }

    public function clientesmasactivospublicarepoAction() {
        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $idUsuario = Zend_Registry::get('id_usuario');

        $modelUser = new Application_Model_DbTable_Usuarios();
        $datosAdmin = $modelUser->traerdatosclienteID($idUsuario);
        foreach ($datosAdmin as $admin) {
            $nombreAdmin = $admin->nombre;
            $apellidoAdmin = $admin->apellido;
        }

        $model = new Application_Model_DbTable_Comentarios();
        $posts = $model->reporte_cliente_mas_activo_publica($from, $to);
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();

        $tipo_reporte = 'Reporte Clientes Mas Activos (Comentarios)';
        $content = '<html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>Reportes - Capicua Restobar.</title>
                    </head>
                    <body>' .
                '<img width=100% height=50px src="images/top_footer.jpg"/>
                        <br/><br/> 
                        <h3><center>' . $tipo_reporte . '</center></h3>' . '<br/>
                        <strong><u>Administrador : </u></strong>' . '        ' . $nombreAdmin . '        ' . $apellidoAdmin . '<br/>
                        <strong><u>Fecha : </u></strong>' . '          ' . date("d/m/Y H:i") . '<br/>';
        if ($from || $to) :
            $content = $content . ' <hr>
                                        <li>
                                           <p>Desde: ' . $from . '</p>
                                       </li> 
                                        <li>
                                           <p>Hasta: ' . $to . '</p>
                                       </li>';

        else :

            $content = $content . '     <h3> Reporte Historico</h3>';
        endif;
        $content = $content . '      <div>
                            <table cellspacing="10%" cellpadding="10%" width=100%>
                               <tr align = center  BGCOLOR="ccff66">
                                <td width="2%"><font><u>Nombre</u></font></td>
                                <td width="2%"><font><u>Usuario</u></font></td>
                                <td width="2%"><font><u>Email</u></font></td>
                                <td width="2%"><font><u>Total de Comentarios</u></font></td>

                               </tr>';
        if (count($posts)) {
            foreach ($posts as $tipo) :
                $content = $content . '<tr align="center" bgcolor="ffff99">';
                if ($tipo) {
                    $content = $content . '<td>' . $tipo->nombre . ' ' . $tipo->apellido . '</td>';
                    if ($tipo->tipo_usuario == 1):

                        $content = $content . '<td>' . 'Adminsitrador' . '</td>';
                    else :
                        $content = $content . '<td>' . 'Cliente ' . '</td>';
                    endif;
                    $content = $content . '<td>' . $tipo->email . '</td>';
                    $content = $content . '<td>' . $tipo->num . '</td>';
                    $content = $content . '</tr>';
                }
            endforeach;
        } else {
            $content = $content . ' <tr>
                                    <td colspan="3">No hay datos</td>
                                </tr>';
        }
        $content = $content . '</table><div></body></html>';

        if ($content != '') {
            //Añadimos la extensión del archivo. Si está vacío el nombre lo creamos 
            $path = 'Reporte publicaciones mas activos-ventas' . date("d/m/Y H:i") . '.pdf';

            //Las opciones del papel del PDF. Si no existen se asignan las siguientes:[*] 

            $paper_1 = 'a4';
            $paper_2 = 'portrait';

            $dompdf = new DOMPDF();
            $dompdf->set_paper($paper_1, $paper_2);
            $dompdf->load_html(utf8_encode($content));
            //ini_set("memory_limit","32M"); //opcional  
            $dompdf->render();
            $mode = false;
            //Creamos el pdf 
            if ($mode == false)
                $dompdf->stream($path);

            //Lo guardamos en un directorio y lo mostramos 
            if ($mode == true)
                if (file_put_contents($path, $dompdf->output()))
                    header('Location: ' . $path);
        }
    }

    public function diezproductosmasvendidosAction() {

        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $model = new Application_Model_DbTable_RelCartaVentas();
        $productos = $model->reporte_diez_productos_mas_vendidos($from, $to);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($productos);
        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
        $this->view->from = $from;
        $this->view->to = $to;
    }

    public function diezproductosmasvendidosrepoAction() {

        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $model = new Application_Model_DbTable_RelCartaVentas();
        $posts = $model->reporte_diez_productos_mas_vendidos($from, $to);
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
        $idUsuario = Zend_Registry::get('id_usuario');

        $modelUser = new Application_Model_DbTable_Usuarios();
        $datosAdmin = $modelUser->traerdatosclienteID($idUsuario);
        foreach ($datosAdmin as $admin) {
            $nombreAdmin = $admin->nombre;
            $apellidoAdmin = $admin->apellido;
        }
        $tipo_reporte = '10 Productos Mas Vendidos';

        $content = '<html>
                                    <head>
                                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                        <title>Reportes - Capicua Restobar.</title>
                                    </head>
                                    <body>' .
                '<img width=100% height=50px src="images/top_footer.jpg"/>
                                        <br/><br/> 
                                        <h3><center>' . $tipo_reporte . '</center></h3>' . '<br/>
                                        <strong><u>Administrador : </u></strong>' . '        ' . $nombreAdmin . '        ' . $apellidoAdmin . '<br/>
                                        <strong><u>Fecha : </u></strong>' . '          ' . date("d/m/Y H:i") . '<br/>';


        if ($from || $to) :
            $content = $content . ' <hr>
                                                                    <li>
                                                                       <p>Desde: ' . $from . '</p>
                                                                   </li> 
                                                                    <li>
                                                                       <p>Hasta: ' . $to . '</p>
                                                                   </li>';

        else :

            $content = $content . '     <h3> Reporte Historico</h3>';
        endif;

        $content = $content . '  <div>
                                            <table cellspacing="10%" cellpadding="10%" width=100%>
                                                <tr align = center  BGCOLOR="ccff66">
                                                    <td width="2%"><font><u>Id Producto</u></font></td>
                                                <td width="2%"><font><u>Nombre Producto</u></font></td>
                                                <td width="2%"><font><u>Tipo Menu</u></font></td>
                                                <td width="2%"><font><u>Total de veces Vendido</u></font></td>

                                                </tr>';
        if (count($posts)) {
            foreach ($posts as $tipo) :
                $content = $content . '<tr align="center" bgcolor="ffff99">';
                if ($tipo) {
                    $content = $content . '<td>' . $tipo->id_producto . '</td>';
                    $content = $content . '<td>' . $tipo->nombre . '</td>';
                    if ($tipo->estado_web == 1):
                        $content = $content . '<td width="2" >NO</td>';
                    else:
                        $content = $content . '<td width="2" >SI</td>';
                    endif;


                    $content = $content . '<td>' . $tipo->cantidad . '</td>';
                    $content = $content . '</tr>';
                }
            endforeach;
        } else {
            $content = $content . ' <tr>
                                                    <td colspan="3">No hay datos</td>
                                                </tr>';
        }
        $content = $content . '</table><div></body></html>';
        if ($content != '') {
            //Añadimos la extensión del archivo. Si está vacío el nombre lo creamos 
            $path = 'Reporte 10 Productos Mas Vendidos.pdf';

            //Las opciones del papel del PDF. Si no existen se asignan las siguientes:[*] 

            $paper_1 = 'a4';
            $paper_2 = 'portrait';

            $dompdf = new DOMPDF();
            $dompdf->set_paper($paper_1, $paper_2);
            $dompdf->load_html(utf8_encode($content));
            //ini_set("memory_limit","32M"); //opcional  
            $dompdf->render();
            $mode = false;
            //Creamos el pdf 
            if ($mode == false)
                $dompdf->stream($path);

            //Lo guardamos en un directorio y lo mostramos 
            if ($mode == true)
                if (file_put_contents($path, $dompdf->output()))
                    header('Location: ' . $path);
        }
    }

    public function diezproductosmenosvendidosAction() {
        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $model = new Application_Model_DbTable_RelCartaVentas();
        $productos = $model->reporte_diez_productos_menos_vendidos($from, $to);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($productos);
        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
        $this->view->from = $from;
        $this->view->to = $to;
    }

    public function diezproductosmenosvendidosrepoAction() {

        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $model = new Application_Model_DbTable_RelCartaVentas();
        $posts = $model->reporte_diez_productos_menos_vendidos($from, $to);
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
        $idUsuario = Zend_Registry::get('id_usuario');

        $modelUser = new Application_Model_DbTable_Usuarios();
        $datosAdmin = $modelUser->traerdatosclienteID($idUsuario);
        foreach ($datosAdmin as $admin) {
            $nombreAdmin = $admin->nombre;
            $apellidoAdmin = $admin->apellido;
        }
        $tipo_reporte = '10 Productos Menos Vendidos';

        $content = '<html>
                                                    <head>
                                                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                                        <title>Reportes - Capicua Restobar.</title>
                                                    </head>
                                                    <body>' .
                '<img width=100% height=50px src="images/top_footer.jpg"/>
                                                        <br/><br/> 
                                                        <h3><center>' . $tipo_reporte . '</center></h3>' . '<br/>
                                                        <strong><u>Administrador : </u></strong>' . '        ' . $nombreAdmin . '        ' . $apellidoAdmin . '<br/>
                                                        <strong><u>Fecha : </u></strong>' . '          ' . date("d/m/Y H:i") . '<br/>';
        if ($from || $to) :
            $content = $content . ' <hr>
                                        <li>
                                           <p>Desde: ' . $from . '</p>
                                       </li> 
                                        <li>
                                           <p>Hasta: ' . $to . '</p>
                                       </li>';

        else :

            $content = $content . '     <h3> Reporte Historico</h3>';
        endif;

        $content = $content . '  <div>
                                                            <table cellspacing="10%" cellpadding="10%" width=100%>
                                                                <tr align = center  BGCOLOR="ccff66">
                                                                    <td width="2%"><font><u>Id Producto</u></font></td>
                                                                <td width="2%"><font><u>Nombre</u></font></td>
                                                                <td width="2%"><font><u>Total de veces Vendido</u></font></td>

                                                                </tr>';
        if (count($posts)) {
            foreach ($posts as $tipo) :
                $content = $content . '<tr align="center" bgcolor="ffff99">';
                if ($tipo) {
                    $content = $content . '<td>' . $tipo->id_producto . '</td>';
                    $content = $content . '<td>' . $tipo->nombre . '</td>';
                    $content = $content . '<td>' . $tipo->cantidad . '</td>';
                    $content = $content . '</tr>';
                }
            endforeach;
        } else {
            $content = $content . ' <tr>
                                                                    <td colspan="3">No hay datos</td>
                                                                </tr>';
        }
        $content = $content . '</table><div></body></html>';
        if ($content != '') {
            //Añadimos la extensión del archivo. Si está vacío el nombre lo creamos 
            $path = 'Reporte 10 Productos Menos Vendidos.pdf';

            //Las opciones del papel del PDF. Si no existen se asignan las siguientes:[*] 

            $paper_1 = 'a4';
            $paper_2 = 'portrait';

            $dompdf = new DOMPDF();
            $dompdf->set_paper($paper_1, $paper_2);
            $dompdf->load_html(utf8_encode($content));
            //ini_set("memory_limit","32M"); //opcional  
            $dompdf->render();
            $mode = false;
            //Creamos el pdf 
            if ($mode == false)
                $dompdf->stream($path);

            //Lo guardamos en un directorio y lo mostramos 
            if ($mode == true)
                if (file_put_contents($path, $dompdf->output()))
                    header('Location: ' . $path);
        }
    }

    public function publicacionesmascomentadasAction() {

        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $model = new Application_Model_DbTable_Comentarios();
        $publicaciones = $model->reporte_publicaciones_mas_comentadas($from, $to);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');
        $paginator = Zend_Paginator::factory($publicaciones);
        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;
        $this->view->from = $from;
        $this->view->to = $to;
    }

    public function publicacionesmascomentadasrepoAction() {

        $from = $this->_getParam('from');
        $to = $this->_getParam('to');

        $model = new Application_Model_DbTable_Comentarios();
        $posts = $model->reporte_publicaciones_mas_comentadas($from, $to);
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
        $idUsuario = Zend_Registry::get('id_usuario');

        $modelUser = new Application_Model_DbTable_Usuarios();
        $datosAdmin = $modelUser->traerdatosclienteID($idUsuario);
        foreach ($datosAdmin as $admin) {
            $nombreAdmin = $admin->nombre;
            $apellidoAdmin = $admin->apellido;
        }
        $tipo_reporte = 'Publicaciones Mas Comentadas';

        $content = '<html>
                                                                    <head>
                                                                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                                                        <title>Reportes - Capicua Restobar.</title>
                                                                    </head>
                                                                    <body>' .
                '<img width=100% height=50px src="images/top_footer.jpg"/>
                                                                        <br/><br/> 
                                                                        <h3><center>' . $tipo_reporte . '</center></h3>' . '<br/>
                                                                        <strong><u>Administrador : </u></strong>' . '        ' . $nombreAdmin . '        ' . $apellidoAdmin . '<br/>
                                                                        <strong><u>Fecha : </u></strong>' . '          ' . date("d/m/Y H:i") . '<br/>';

        if ($from || $to) :
            $content = $content . ' <hr>
                                        <li>
                                           <p>Desde: ' . $from . '</p>
                                       </li> 
                                        <li>
                                           <p>Hasta: ' . $to . '</p>
                                       </li>';

        else :

            $content = $content . '     <h3> Reporte Historico</h3>';
        endif;
        $content = $content . '      <div>
                                                                            <table cellspacing="10%" cellpadding="10%" width=100%>
                                                                                <tr align = center  BGCOLOR="ccff66">
                                                                                    <td width="2%"><font><u>Titulo Publicacion</u></font></td>
                                                                                <td width="4%"><font><u>Contenido</u></font></td>
                                                                                <td width="2%"><font><u>Fecha Publicacion</u></font></td>
                                                                                <td width="2%"><font><u>Total de Comentarios</u></font></td>

                                                                                </tr>';
        if (count($posts)) {
            foreach ($posts as $tipo) :
                $content = $content . '<tr align="center" bgcolor="ffff99">';
                if ($tipo) {
                    $content = $content . '<td>' . $tipo->titulo . '</td>';
                    $content = $content . '<td>' . $tipo->contenido . '</td>';
                    $content = $content . '<td>' . $tipo->fecha_publicacion . '</td>';
                    $content = $content . '<td>' . $tipo->num . '</td>';
                    $content = $content . '</tr>';
                }
            endforeach;
        } else {
            $content = $content . ' <tr>
                                                                                    <td colspan="3">No hay datos</td>
                                                                                </tr>';
        }
        $content = $content . '</table><div></body></html>';
        if ($content != '') {
            //Añadimos la extensión del archivo. Si está vacío el nombre lo creamos 
            $path = 'Reporte Publicaciones mas Comentadas.pdf';

            //Las opciones del papel del PDF. Si no existen se asignan las siguientes:[*] 

            $paper_1 = 'a4';
            $paper_2 = 'portrait';

            $dompdf = new DOMPDF();
            $dompdf->set_paper($paper_1, $paper_2);
            $dompdf->load_html(utf8_encode($content));
            //ini_set("memory_limit","32M"); //opcional  
            $dompdf->render();
            $mode = false;
            //Creamos el pdf 
            if ($mode == false)
                $dompdf->stream($path);

            //Lo guardamos en un directorio y lo mostramos 
            if ($mode == true)
                if (file_put_contents($path, $dompdf->output()))
                    header('Location: ' . $path);
        }
    }

}