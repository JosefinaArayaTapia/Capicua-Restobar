<?php

class Application_Model_DbTable_Comentarios extends Zend_Db_Table_Abstract {

    protected $_name = 'comentarios';
    protected $_referenceMap = array(
        'publicaciones' => array(
            'columns' => array('id_publicacion'),
            'refTableClass' => 'publicaciones',
            'refColumns' => array('id_publicacion'),
            'onDelete' => self::CASCADE
            ));

    public function todosloscomentarios() {

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from('comentarios', array('comentarios.*', 'a.foto_perfil'))
                ->join(array('a' => 'usuarios'), 'comentarios.id_usuario = a.id_usuario')
                ->order('fecha DESC');



        return $this->fetchAll($select);
    }

    public function agregarcomentario($cuerpo, $fecha, $id_publicacion, $id_usuario) {

        $data = array('cuerpo' => $cuerpo, 'fecha' => $fecha,
            'id_publicacion' => $id_publicacion, 'id_usuario' => $id_usuario);

        $this->insert($data, 0);
    }

    public function contarcomentariosunapublicacion($id) {

        $select = $this->select();
        $select->from($this->_name, array('count(*) as amount'))
                ->where('id_publicacion=?', $id);


        $rows = $this->fetchAll($select);
        return($rows[0]->amount);
    }

    public function obtenerRow($id) {

        $id = (int) $id;
        $row = $this->find($id)->current();
        return $row;
    }

    public function reporte_cliente_mas_activo_publica($fecha_inicio, $fecha_fin) {

        if ($fecha_inicio != NULL)
            $fecha_inicio = date("Y-m-d H:i:s", strtotime($fecha_inicio)); // pasar hora inicio EJ: 2013-08-08 00:00:00
        if ($fecha_fin != NULL)
            $fecha_fin = date("Y-m-d 23:59:59", strtotime($fecha_fin)); // pasar hora fin EJ: 2013-08-08 23:59:59 (se coloca esa hora o sino no pesca las del dia)

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->distinct();

        $select->from(array('CO' => 'comentarios'), array('id_comentario', 'US.id_usuario','US.tipo_usuario', 'US.nombre', 'num' => 'COUNT(CO.id_comentario)'))
                ->join(array('US' => 'usuarios'), 'CO.id_usuario=US.id_usuario')
                ->group('US.nombre')
                ->order('num DESC')
        ;

        if ($fecha_inicio != NULL && $fecha_fin != NULL) { // entra solo si trae las dos fechas
            $select->where("CO.fecha between '$fecha_inicio' and '$fecha_fin'"); // se hace un  between simple  entre dos fechas
        } else {
            if ($fecha_inicio != NULL) { // solo si trae la inicial
                $select->where("CO.fecha >= ?", $fecha_inicio);
            }
            if ($fecha_fin != NULL) { //solo si trae la final
                $select->where("CO.fecha <= ?", $fecha_fin);
            }
        }


        return $this->fetchAll($select);
    }

    public function reporte_publicaciones_mas_comentadas($inicio, $fin) {
        if ($inicio != NULL)
            $inicio = date("Y-m-d H:i:s", strtotime($inicio)); // pasar hora inicio EJ: 2013-08-08 00:00:00
        if ($fin != NULL)
            $fin = date("Y-m-d 23:59:59", strtotime($fin)); // pasar hora fin EJ: 2013-08-08 23:59:59 (se coloca esa hora o sino no pesca las del dia)

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->distinct();

        $select->from(array('CO' => 'comentarios'), array('PU.titulo', 'PU.contenido', 'PU.fecha_publicacion', 'PU.fecha_vigencia', 'num' => 'COUNT(CO.id_publicacion)'))
                ->join(array('PU' => 'publicaciones'), 'CO.id_publicacion=PU.id_publicacion')
                ->group('PU.titulo')
                ->order('num DESC')
        ;
        if ($inicio != NULL && $fin != NULL) { // entra solo si trae las dos fechas
            $select->where("CO.fecha between '$inicio' and '$fin'"); // se hace un  between simple  entre dos fechas
        } else {
            if ($inicio != NULL) { // solo si trae la inicial
                $select->where("CO.fecha >= ?", $inicio);
            }
            if ($fin != NULL) { //solo si trae la final
                $select->where("CO.fecha <= ?", $fin);
            }
        }


        return $this->fetchAll($select);
    }

}

