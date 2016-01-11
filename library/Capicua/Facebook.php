<?php

class Capicua_Facebook {

    private static $fb;

    private static function getFB() {
        $permisitos = 'email, user_birthday, user_location, publish_stream, manage_pages, offline_access';

        if (self::$fb) {
            return self::$fb;
        }

        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');

        $options = $bootstrap->getOptions();

        $fb = New Facebook_Facebook(array(
                    'appId' => $options['facebook']['appid'],
                    'secret' => $options['facebook']['appsecret'],
                    'cookie' => true,
                    'req_perms' => $permisitos
                ));

        self::$fb = $fb;

        return self::$fb;
    }

    public static function __callStatic($name, $args) {

        $callback = array(self::getFB(), $name);
        return call_user_func_array($callback, $args);
    }

}

?>