<?php

class Application_Model_DbTable_Reservas extends Zend_Db_Table_Abstract {

    protected $_name = 'reservas';
    protected $_primary = 'id_reserva';

    public function agregarreserva($personas, $fecha_reserva, $observacion, $fechavigencia, $idUsuario, $id_estado_reserva) {
        $data = array('cantidad_personas' => $personas, 'fecha_realizacion' => $fechavigencia,
            'fecha_reserva' => $fecha_reserva, 'observacion' => $observacion, 'id_usuario' => $idUsuario,
            'id_estado_reserva' => $id_estado_reserva);

        $this->insert($data, 0);
    }

    public function traerreservas($id) {
        $query = $this->select()
                ->from(array('u' => 'reservas'))
                ->where('u.id_usuario=?', $id)
                ->order('u.fecha_reserva ASC')
                ->setIntegrityCheck(false);

        $rowset = $this->fetchAll($query);
        return $rowset;
    }

    public function mostrarreservas() {

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from('usuarios', array('usuarios.nombre','usuarios.apellido' ,'usuarios.email', 'a.*'))
                ->join(array('a' => 'reservas'), 'usuarios.id_usuario = a.id_usuario')
                ->order('a.fecha_reserva DESC')
                ->order('a.id_estado_reserva ASC');

        return $this->fetchAll($select);



//        $sql = "select usuarios.nombre,usuarios.email,reservas.cantidad_personas,
//            reservas.id_reserva, reservas.fecha_realizacion, reservas.fecha_reserva 
//		from usuarios
//	 join reservas on reservas.id_usuario=usuarios.id_usuario";
//        $resultset = $this->fetchAll($sql);
//        return $resultset;
    }

    public function mostrarunareserva($idreserva) {

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from('usuarios', array('usuarios.nombre', 'usuarios.email', 'a.*'))
                ->join(array('a' => 'reservas'), 'usuarios.id_usuario = a.id_usuario')
                ->where('a.id_reserva=?', $idreserva);


        return $this->fetchAll($select);
    }

    public function cambiarestadoreserva($estado, $id) {
        
              
        $data = array('id_estado_reserva' => $estado);

        //$this->update cambia datos de reserva con id_reserva= $id
        $this->update($data, 'id_reserva = ' . (int) $id);
    }

}

