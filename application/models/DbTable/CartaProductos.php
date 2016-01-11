<?php
 require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_CartaProductos extends Zend_Db_Table_Abstract {

    protected $_name = 'carta_productos';
    protected $_primary = 'id_producto';

    public function getAll() {
        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from(array('b' => 'carta_productos'))
                ->join(array('a' => 'tipo_productos'), 'b.id_tipo_producto = a.id_tipo_producto')
                ->where('b.estado_web =?', 0);

        return $this->fetchAll($select);
    }

    public function getproductos() {

        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from(array('b' => 'carta_productos'))
                ->join(array('a' => 'tipo_productos'), 'b.id_tipo_producto = a.id_tipo_producto');

        return $this->fetchAll($select);
    }

    public function getproductosMenu() {
        $select = $this->select();
        $select->setIntegrityCheck(false)
                ->from(array('b' => 'carta_productos'))
                ->join(array('a' => 'tipo_productos'), 'b.id_tipo_producto = a.id_tipo_producto')
                ->where('b.estado_web =?', 1);

        return $this->fetchAll($select);
    }

    public function nombremenus() {
        $query = $this->select()
                ->where('estado_web=?', 0); //traer todos los propductos que tengan estado web igual a 0 (tipo menu)
        $productoscarta = $this->fetchAll($query);
        return $productoscarta;
    }

    public function cartaproductos() {
        $sql = "SELECT * FROM carta_productos
                WHERE estado_web=1";
        $resultset = $this->getAdapter()->query($sql);
        $rowset = $resultset->fetchAll();
        $data = array();
        foreach ($rowset as $row) {
            $data[$row->id_producto] = $row->nombre;
        }

        return $data;
    }

    public function agregarproducto($nombre, $descripcion, $precio, $imagen, $puntos_producto, $estado_web, $id_tipo_producto, $fecha) {

        if (!$fecha) {
            $fecha = '2013-06-05 17:04:48';
        }
        $data = array('nombre' => $nombre, 'descripcion' => $descripcion,
            'precio' => $precio, 'puntos_producto' => $puntos_producto, 'imagen' => $imagen,
            'estado_web' => $estado_web, 'id_tipo_producto' => $id_tipo_producto,
            'fecha_publicacion' => $fecha, 'fecha_vigencia' => $fecha);

        $this->insert($data, 0);
    }

    public function obtenerRow($id) {

        $id = (int) $id;
        $row = $this->find($id)->current();
        return $row;
    }

    public function editarmenus($name, $descripcion, $precio, $puntos, $tipo, $foto, $id) {

        $data = array('nombre' => $name, 'descripcion' => $descripcion, 'precio' => $precio, 'id_tipo_producto' => $tipo, 'imagen' => $foto, 'puntos_producto' => $puntos);
        $this->update($data, 'id_producto = ' . (int) $id);
    }

    public function editarproductos($nombre, $descripcion, $tipo, $precio, $puntos, $foto, $id) {
        if ($foto == NULL) {
            $data = array('nombre' => $nombre, 'descripcion' => $descripcion, 'precio' => $precio, 'id_tipo_producto' => $tipo, 'puntos_producto' => $puntos);
        } else {
            $data = array('nombre' => $nombre, 'descripcion' => $descripcion, 'precio' => $precio, 'id_tipo_producto' => $tipo, 'puntos_producto' => $puntos, 'imagen' => $foto);
        }
        $this->update($data, 'id_producto = ' . (int) $id);
    }

}

