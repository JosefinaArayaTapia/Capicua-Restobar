<?php

class Application_Model_DbTable_TipoPublicaciones extends Zend_Db_Table_Abstract {

    protected $_name = 'tipo_publicaciones';
    protected $_primary = 'id_tipo_publicacion';

    public function selecttipopublicaciones() {

        $rowset = $this->fetchAll();

        $data = array();
        foreach ($rowset as $row) {
            $data[$row->id_tipo_publicacion] = $row->nombre_tipo;
        }

        return $data;
    }

    public function traertipos() {
        $query = 'select nombre_tipo FROM tipo_publicaciones';

        $rowset = $this->fetchAll($query);
        return $rowset;
    }

    public function traeruntipopublicacion($id) {
        $select = $this->select();
        $select->where('id_tipo_publicacion=?', $id);

        $resultset = $this->fetchAll($select);
        return $resultset;
    }

    public function traertipoproductos() {
        $admins = $this->fetchAll();
        return $admins;
    }

    public function agregartipo($nom) {
        $data = array('nombre_tipo' => $nom);
        $this->insert($data, 0);
    }

    public function vervisibilidad($id) {
        $select = $this->select();
        $select->from($this->_name, array('visibilidad'))
                ->where('id_tipo_publicacion=?', $id);
        $rowset = $this->fetchAll($select);
        foreach ($rowset as $row) {
            $visi = $row->visibilidad;
        }
        return $visi;
    }

    public function editarvisibilidad($id, $visibilidad) {
        $data = array('visibilidad' => $visibilidad);
        $this->update($data, 'id_tipo_publicacion = ' . (int) $id);
    }

    public function editartipopublicacion($id, $nombre) {
        $data = array('nombre_tipo' => $nombre);
        $this->update($data, 'id_tipo_publicacion = ' . (int) $id);
    }

}

