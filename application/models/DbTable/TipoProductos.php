<?php

class Application_Model_DbTable_TipoProductos extends Zend_Db_Table_Abstract {

    protected $_name = 'tipo_productos';
    protected $_primary = 'id_tipo_producto';

    public function numerotipo() {

        $sql = "SELECT (COUNT(*)) as numero from tipo_productos WHERE tipo_menu =0";
        $resultset = $this->getAdapter()->query($sql);
        return $resultset->fetchAll();
    }

    public function nombretipo() {
        $select = $this->select();

        $select->from($this->_name,array('nombre_tipo','id_tipo_producto'))
                ->where('tipo_menu=?',0)
                ->where('visibilidad=?',1);
        
        $resultset = $this->fetchAll($select);
        return $resultset;
    }

    public function nombretipomenu() {

        $sql = "select tp.nombre_tipo,tp.id_tipo_producto
                from   tipo_productos as tp 
                where tipo_menu=1 and visibilidad=1
                ";
        $resultset = $this->getAdapter()->query($sql);
        return $resultset->fetchAll();
    }

    public function todoslosproductos() {
        $rowset = $this->fetchAll();
        $data = array();
        foreach ($rowset as $row) {
            $data[$row->id_tipo_producto] = $row->nombre_tipo;
        }
        return $data;
    }

    public function select_menus() {

        $query = $this->select()
                ->where('tipo_menu=?', 1); //traer todos los nombre_menu en los cuales  tipo_menu = 1, lo cual significa que es un menu

        $productoscarta = $this->fetchAll($query);
        $data = array();
        foreach ($productoscarta as $row) {
            $data[$row->id_tipo_producto] = $row->nombre_tipo;
        }
        return $data;
    }

    public function select_tipoProducto() {

        $query = $this->select()
                ->where('tipo_menu=?', 0); //traer todos los nombre_menu en los cuales  tipo_menu = 1, lo cual significa que es un menu

        $productoscarta = $this->fetchAll($query);
        $data = array();
        foreach ($productoscarta as $row) {
            $data[$row->id_tipo_producto] = $row->nombre_tipo;
        }
        return $data;
    }

    public function traertodo() {
        return $this->fetchAll();
    }

    public function traeruntipo($id) {
        $query = $this->select()
                ->where('id_tipo_producto=?', $id);
        $tipomenu = $this->fetchAll($query);
        return $tipomenu;
    }

    public function editaruntipo($nombre, $tipo, $id) {

        $data = array('nombre_tipo' => $nombre, 'tipo_menu' => $tipo);

        $this->update($data, 'id_tipo_producto = ' . (int) $id);
    }

    public function agregaruntipo($nombre, $tipo, $visibilidad) {
        $data = array('nombre_tipo' => $nombre, 'tipo_menu' => $tipo,'visibilidad'=>$visibilidad);

        $this->insert($data, 0);
    }

    public function vervisibilidad($id) {
        $select = $this->select();
        $select->from($this->_name, array('visibilidad'))
                ->where('id_tipo_producto=?', $id);
        $rowset = $this->fetchAll($select);
        foreach ($rowset as $row) {
            $visi = $row->visibilidad;
        }
        return $visi;
    }

    public function editarvisibilidad($id, $visibilidad) {
        $data = array('visibilidad' => $visibilidad);
        $this->update($data, 'id_tipo_producto = ' . (int) $id);
    }

}

