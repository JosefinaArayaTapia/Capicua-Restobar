<?php

class ContactoController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
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
                $view->apellido = $user->apellido;

                $view->ultimo = $user->ultimo_acceso;
                $view->permiso = $permisoadmin;
            }
        }


        $form = new Application_Form_Enviarmensaje();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $nombre = $form->getValue('nombre');
                $contenido = $form->getValue('mensaje');
                $email = $form->getValue('email');
                //Aqui ya estamos seguros de que los datos son validos    
                //Enviamos el email:  
                // $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'correo@dominio.com', 'password' => '######');  

                $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'capicua.contacto@gmail.com', 'password' => 'capicuarestobar');

                $smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);

                $mail = new Zend_Mail('utf-8');
                $mail->setFrom($email, $nombre);
                $mail->addTo('capicua.contacto@gmail.com', 'Receptor');
                $mail->setSubject('Formulario de Contacto');
                $mail->setBodyHtml("<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			<html xmlns='http://www.w3.org/1999/xhtml'> 
				<head> 
					<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
				</head>
				<div id='wrap' style='font-family:Arial, Helvetica, sans-serif; border:thick solid #ebebeb; background-color:#fafafa; width: 660px; height: auto; margin:auto;
				text-align:center;text-align : justify; position:left;'> 
					<div id='header' style='width: 660px; height: 100px; margin:auto; text-align:center; font-size:24px; display:block;'>
						<img src='http://capicua-restobar.cl/images/logo.png' />
					</div>
					<div id='texto' style='width:610px; text-align : justify; margin: 20px 10px 10px 20px;'>
						<h2 style='color:'#BF1515''>Mensaje de :  ".$nombre."</h2>
						<h2 style='color=#0101DF'>Contenido de mensaje:</h2>
						<h2> ".$contenido."</h2><br><br><br><br>
						<p>Para poder responder este mensaje se debe hacer al correo electornico siguiente:</p>
						<p><b>".$email."</b></p>
						
					</div><!--fin texto-->
					<br/>
					<div id='footer' style='width: 660px; text-align:center; text-align : justify;'>
						<p style='color:#333;'>Contactenos Nuestra pagina web es : </p>
						<p>www.capicua-restobar.cl</p>
                        <p>Nuestro correo electronico es:</p>
						<p>capicua.contacto@gmail.com</p>
                         <p>Ubiquenos en la direccion San Antonio #1085, Viña del Mar</p>
					</div><!--fin footer-->
				</div> <!--fin wrap-->
			</html>"
                );
                $mail->send($smtpConnection);
                //vamos de nuevo a la página principal 
                return $this->_redirect('/index');
            }
        }
        $this->view->formuluario = $form;
    }

}

