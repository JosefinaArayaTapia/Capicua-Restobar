<?php

class Application_Model_DbTable_RelCartaVentas extends Zend_Db_Table_Abstract {

    protected $_name = 'rel_carta_ventas';

    public function agregarProductosVenta($idventa, $idproducto, $cantidad) {

        $data = array('id_venta' => $idventa, 'id_producto' => $idproducto,
            'cantidad_producto' => $cantidad);
        $this->insert($data, 0);
    }

    public function reporte_diez_productos_mas_vendidos($inicio, $fin) {
        if ($inicio != NULL)
            $inicio = date("Y-m-d H:i:s", strtotime($inicio)); // pasar hora inicio EJ: 2013-08-08 00:00:00
        if ($fin != NULL)
            $fin = date("Y-m-d 23:59:59", strtotime($fin)); // pasar hora fin EJ: 2013-08-08 23:59:59 (se coloca esa hora o sino no pesca las del dia)


        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->distinct();

        $select->from(array('RE' => 'rel_carta_ventas'), array('CP.id_producto', 'CP.estado_web', 'CP.nombre', 'cantidad' => 'SUM(RE.cantidad_producto)'))
                ->join(array('VE' => 'ventas'), 'RE.id_venta = VE.id_venta')
                ->join(array('CP' => 'carta_productos'), 'CP.id_producto=RE.id_producto')
                ->group('RE.id_producto')
                ->order('cantidad DESC')
                ->limit(10)
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

    public function reporte_diez_productos_menos_vendidos($inicio, $fin) {
        if ($inicio != NULL)
            $inicio = date("Y-m-d H:i:s", strtotime($inicio)); // pasar hora inicio EJ: 2013-08-08 00:00:00
        if ($fin != NULL)
            $fin = date("Y-m-d 23:59:59", strtotime($fin)); // pasar hora fin EJ: 2013-08-08 23:59:59 (se coloca esa hora o sino no pesca las del dia)


        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->distinct();

        $select->from(array('RE' => 'rel_carta_ventas'), array('CP.id_producto', 'CP.estado_web', 'CP.nombre', 'cantidad' => 'SUM(RE.cantidad_producto)'))
                ->join(array('VE' => 'ventas'), 'RE.id_venta = VE.id_venta')
                ->join(array('CP' => 'carta_productos'), 'CP.id_producto=RE.id_producto')
                ->group('RE.id_producto')
                ->order('cantidad ASC')
                ->limit(10)
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

