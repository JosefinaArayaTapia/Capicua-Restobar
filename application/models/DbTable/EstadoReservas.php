<?php

class Application_Model_DbTable_EstadoReservas extends Zend_Db_Table_Abstract {

    protected $_name = 'estado_reservas';

    public function traerestadoreserva() {
        $rowset = $this->fetchAll();
        $data = array();
        foreach ($rowset as $row) {
            $data[$row->id_estado_reserva] = $row->nombre_estado;
        }
        return $data;
    }

}

