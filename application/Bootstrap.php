<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initConfig() {

        date_default_timezone_set('America/Santiago');

        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }

    protected function _initErrorDisplay() {
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->throwExceptions(true);
    }

    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');      
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->doctype('XHTML1_STRICT');
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }

    protected function _initSession() {

        Zend_Session::start();
    }

}

