<?php

class Application_Model_DbTable_Publicaciones extends Zend_Db_Table_Abstract {

    protected $_name = 'publicaciones';
    protected $_primary = 'id_publicacion';
//    protected $_dependentTables = array('comentarios');
    protected $_referenceMap = array(
        'usuarios' => array(
            'columns' => 'id_usuario',
            'refTableClass' => 'usuarios',
            'refColumns' => 'id_usuario'
        ),
        'tipo_publicacion' => array(
            'columns' => 'id_tipo_publicacion',
            'refTableClass' => 'tipo_publicacion',
            'refColumns' => 'id_tipo_publicacion'
            ));

    public function getAll() {

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from($this->_name, array('publicaciones.*', 'usuarios.*', 'tipo_publicaciones.nombre_tipo'))
                ->join('usuarios', 'publicaciones.id_usuario=usuarios.id_usuario')
                ->join('tipo_publicaciones', 'publicaciones.id_tipo_publicacion=tipo_publicaciones.id_tipo_publicacion')
                ->where('tipo_publicaciones.visibilidad=?', 1)
                ->order('publicaciones.fecha_publicacion DESC');
        ;
        return $this->fetchAll($select);
    }

    public function listar_todos() {

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from($this->_name, array('publicaciones.*', 'usuarios.*', 'tipo_publicaciones.nombre_tipo'))
                ->join('usuarios', 'publicaciones.id_usuario=usuarios.id_usuario')
                ->join('tipo_publicaciones', 'publicaciones.id_tipo_publicacion=tipo_publicaciones.id_tipo_publicacion')
                ->order('publicaciones.fecha_publicacion DESC');
        ;
        return $this->fetchAll($select);
    }

    public function save($titulo, $contenido, $imagen, $fechavigencia, $idusuario, $idtipopublicacion, $fecha_publicacion) {

        $data = array('titulo' => $titulo, 'contenido' => $contenido, 'fecha_publicacion' => $fecha_publicacion,
            'imagen' => $imagen, 'fecha_vigencia' => $fechavigencia, 'id_usuario' => $idusuario,
            'id_tipo_publicacion' => $idtipopublicacion);

        $this->insert($data, 0);
    }

    public function traerdosultimas() {
        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from($this->_name, array('publicaciones.*', 'usuarios.*', 'tipo_publicaciones.nombre_tipo'))
                ->join('usuarios', 'publicaciones.id_usuario=usuarios.id_usuario')
                ->join('tipo_publicaciones', 'publicaciones.id_tipo_publicacion=tipo_publicaciones.id_tipo_publicacion')
                ->order('publicaciones.fecha_publicacion DESC')
                ->limit(2)
        ;

        return $this->fetchAll($select);
    }

    public function obtenerRow($id) {

        $id = (int) $id;
        $row = $this->find($id)->current();
        return $row;
    }

    public function editarPublicidad($titulo, $contenido, $imagen, $fecha_vigencia, $tipo, $id) {
        $data = array('titulo' => $titulo, 'contenido' => $contenido, 'id_tipo_publicacion' => $tipo, 'imagen' => $imagen, 'fecha_vigencia' => $fecha_vigencia);

        
        $this->update($data, 'id_publicacion = ' . (int) $id);
    }

    public function buscar() {

        $select = $this->_db->select()
                ->from($this->_name);
        $results = $this->getAdapter()->fetchAll($select);
        return $results;
    }

    public function mostrarunapublicacion($id_publicacion) {

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from($this->_name, array('publicaciones.*', 'usuarios.*', 'tipo_publicaciones.nombre_tipo'))
                ->join('usuarios', 'publicaciones.id_usuario=usuarios.id_usuario')
                ->join('tipo_publicaciones', 'publicaciones.id_tipo_publicacion=tipo_publicaciones.id_tipo_publicacion')
                ->where('id_publicacion=?', $id_publicacion);
        return $this->fetchAll($select);
    }

}
