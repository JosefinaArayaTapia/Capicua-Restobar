<?php

class Application_Model_DbTable_Ventas extends Zend_Db_Table_Abstract {

    protected $_name = 'ventas';
    protected $_referenceMap = array(
        'usuarios' => array(
            'columns' => 'id_usuario',
            'refTableClass' => 'usuarios',
            'refColumns' => 'id_usuario',
            'onDelete' => self::CASCADE
            ));

    public function crearventa($total, $mesa, $fecha, $puntos_venta, $descuento, $observacion, $id_usuario) {


        $data = array('total' => $total, 'mesa' => $mesa,
            'fecha' => $fecha, 'puntos_venta' => $puntos_venta, 'descuento' => $descuento, 'observacion' => $observacion,
            'id_usuario' => $id_usuario);

        $this->insert($data, 0);

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        return $db->lastInsertId();
    }

    public function allventas() {
        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->distinct();

        $select->from(array('VE' => 'ventas'), array('id_venta', 'fecha', 'total', 'puntos_venta', 'mesa', 'observacion', 'CP.nombre', 'rel.cantidad_producto', 'CP.precio'))
                ->from(array('US' => 'usuarios'), array('cliente' => 'nombre', 'id_usuario', 'apellido'))
                ->join(array('rel' => 'rel_carta_ventas'), 'rel.id_venta = VE.id_venta')
                ->join(array('CP' => 'carta_productos'), 'rel.id_producto = CP.id_producto')
                ->where('VE.id_usuario = US.id_usuario')
                ->where('US.visibilidad=1')
                ->order('fecha DESC')
        ;

        return $this->fetchAll($select);
    }

    public function verventas() {
        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->distinct();
       
        $select->from(array('VE' => 'ventas'), array('id_venta', 'fecha', 'total', 'puntos_venta', 'mesa', 'observacion'))
                ->from(array('US' => 'usuarios'), array('cliente' => 'nombre', 'id_usuario', 'apellido'))
                ->where('VE.id_usuario = US.id_usuario')
                ->where('US.visibilidad=1')
                ->order('fecha DESC')
        ;

       
        return $this->fetchAll($select);
    }

    public function obtenerRow($id) {

        $id = (int) $id;
        $row = $this->find($id)->current();
        return $row;
    }

    public function ventaporcliente($id_cliente) {


        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->distinct();

        $select->from(array('VE' => 'ventas'), array('id_venta', 'fecha', 'total', 'puntos_venta', 'descuento', 'rel.cantidad_producto', 'mesa', 'observacion'))
                ->from(array('US' => 'usuarios'), array('cliente' => 'nombre', 'id_usuario', 'apellido'))
                ->join(array('rel' => 'rel_carta_ventas'), 'rel.id_venta = VE.id_venta')
                ->join(array('CP' => 'carta_productos'), 'rel.id_producto = CP.id_producto')
                ->where('VE.id_usuario = US.id_usuario')

        ;
        $select->where('US.id_usuario=?', $id_cliente)
                ->order('fecha DESC');
        return $this->fetchAll($select);
    }

    public function reporte_venta_cliente($id_cliente, $fecha_inicio, $fecha_fin) {

        if ($fecha_inicio != NULL)
            $fecha_inicio = date("Y-m-d H:i:s", strtotime($fecha_inicio)); // pasar hora inicio EJ: 2013-08-08 00:00:00
        if ($fecha_fin != NULL)
            $fecha_fin = date("Y-m-d 23:59:59", strtotime($fecha_fin)); // pasar hora fin EJ: 2013-08-08 23:59:59 (se coloca esa hora o sino no pesca las del dia)


        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->distinct();

        $select->from(array('VE' => 'ventas'), array('id_venta', 'fecha', 'total', 'descuento', 'puntos_venta', 'mesa', 'observacion', 'CP.nombre', 'rel.cantidad_producto', 'CP.precio'))
                ->from(array('US' => 'usuarios'), array('cliente' => 'nombre', 'id_usuario', 'apellido'))
                ->join(array('rel' => 'rel_carta_ventas'), 'rel.id_venta = VE.id_venta')
                ->join(array('CP' => 'carta_productos'), 'rel.id_producto = CP.id_producto')
                ->where('VE.id_usuario = US.id_usuario')
        ;
        if ($id_cliente != NULL) {
            $select->where('US.id_usuario=?', $id_cliente);
            $select->order('VE.fecha DESC');
        } else {
            $select->order('US.nombre ASC');
            $select->order('VE.fecha DESC');
        }


        if ($fecha_inicio != NULL && $fecha_fin != NULL) { // entra solo si trae las dos fechas
            $select->where("VE.fecha between '$fecha_inicio' and '$fecha_fin'"); // se hace un  between simple  entre dos fechas
        } else {
            if ($fecha_inicio != NULL) { // solo si trae la inicial
                $select->where("VE.fecha >= ?", $fecha_inicio);
            }
            if ($fecha_fin != NULL) { //solo si trae la final
                $select->where("VE.fecha <= ?", $fecha_fin);
            }
        }


        return $this->fetchAll($select);
    }

    public function reporte_cliente_mas_activo_venta($inicio, $fin) {

        if ($inicio != NULL)
            $inicio = date("Y-m-d H:i:s", strtotime($inicio)); // pasar hora inicio EJ: 2013-08-08 00:00:00
        if ($fin != NULL)
            $fin = date("Y-m-d 23:59:59", strtotime($fin)); // pasar hora fin EJ: 2013-08-08 23:59:59 (se coloca esa hora o sino no pesca las del dia)

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->distinct();

        $select->from(array('VE' => 'ventas'), array('id_venta', 'US.nombre', 'US.email', 'US.id_usuario', 'num' => 'COUNT(VE.id_usuario)'))
                ->join(array('US' => 'usuarios'), 'VE.id_usuario=US.id_usuario')
                ->group('US.id_usuario')
                ->order('num DESC')
        ;
        if ($inicio != NULL && $fin != NULL) { // entra solo si trae las dos fechas
            $select->where("VE.fecha between '$inicio' and '$fin'"); // se hace un  between simple  entre dos fechas
        } else {
            if ($inicio != NULL) { // solo si trae la inicial
                $select->where("VE.fecha >= ?", $inicio);
            }
            if ($fin != NULL) { //solo si trae la final
                $select->where("VE.fecha <= ?", $fin);
            }
        }


        return $this->fetchAll($select);
    }

}

