<?php

require_once ('../application/models/DbTable/CartaProductos.php');

/**
 * Description of CartaProductosTest
 *
 * @author Josefina
 */
class CartaProductosTest extends ControllerTestCase {

    public function setUp() {
        parent::setUp();
        $this->stats = new Application_Model_DbTable_CartaProductos();
    }

}

