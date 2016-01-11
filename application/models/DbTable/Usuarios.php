<?php

class Application_Model_DbTable_Usuarios extends Zend_Db_Table_Abstract {
    /* Recordar:                       
     * Tipo_usuario (INT): Identifica si es Cliente(2) u Administrador(1) 
     * Permiso (INT): Identifica el permiso de los SuperAdminstradores(1), Administradores (2)- Cliente (0)
     */

    protected $_name = 'usuarios';
    protected $_primary = 'id_usuario';

    public function save($nombre, $apellido, $email, $telefono, $domicilio, $fotoperfil, $puntos, $contraseña, $tipo_usuario, $permiso, $fechaalta, $ultimoacceso, $visibilidad) {

        $data = array('nombre' => ucfirst($nombre), 'apellido' => ucfirst($apellido),
            'email' => $email, 'telefono' => $telefono, 'domicilio' => ucfirst($domicilio),
            'foto_perfil' => $fotoperfil, 'puntos_acumulados' => $puntos,
            'visibilidad' => $visibilidad, 'password' => $contraseña,
            'tipo_usuario' => $tipo_usuario, 'permiso' => $permiso,
            'fecha_alta' => $fechaalta, 'ultimo_acceso' => $ultimoacceso);
        $this->insert($data, 0);
    }

    public function traerdatoscliente($email) {

        $query = $this->select()
                ->from(array('u' => 'usuarios'))
                ->where('u.email=?', $email)
                ->setIntegrityCheck(false);

        $rowset = $this->fetchAll($query);
        return $rowset;
    }

    public function getAsKeyValue($id_cliente) {
        $select = $this->select()
                ->where('id_usuario = ?', $id_cliente);
        $rowset = $this->fetchAll($select);
        foreach ($rowset as $row) {
            $puntos = $row->puntos_acumulados;
        }
//        var_dump($puntos);
//        exit();
        return $puntos;
    }

    public function getAsKeyValueJSON($id_cliente) {
        $select = $this->select()
                ->from($this->_name, array('puntos_acumulados'))
                ->where('id_usuario = ?', $id_cliente);
        $rowset = $this->fetchAll($select)->toArray();
        return $rowset;
    }

    public function editarperfil($nombre, $apellido, $telefono, $domicilio, $foto, $id) {

        $data = array('nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono,
            'domicilio' => $domicilio,
            'foto_perfil' => $foto,
        );

        $this->update($data, 'id_usuario = ' . (int) $id);
    }

    public function select_clientes() {
        $cliente = 2;
        $query = $this->select()
                ->from(array('u' => 'usuarios'))
                ->where('tipo_usuario=?', $cliente)
                ->where('visibilidad=?', 1)
                ->setIntegrityCheck(false);

        $rowset = $this->fetchAll($query);
        $data = array();
        foreach ($rowset as $row) {
            $data[$row->id_usuario] = $row->nombre . '  ' . $row->apellido;
        }
        return $data;
    }

    public function traerdatosclienteID($id) {


        $query = $this->select()
                ->from(array('u' => 'usuarios'))
                ->where('u.id_usuario=?', $id)
                ->setIntegrityCheck(false);

        $rowset = $this->fetchAll($query);
        return $rowset;
    }

    public function editarpuntosacumulados($puntos, $id) {
        $data = array('puntos_acumulados' => $puntos);
        $this->update($data, 'id_usuario = ' . (int) $id);
    }

    public function traerdatosclientes() {
        $cliente = 2;
        $query = $this->select()
                ->from(array('u' => 'usuarios'))
                ->where('tipo_usuario=?', $cliente)
                ->setIntegrityCheck(false);

        $rowset = $this->fetchAll($query);
        return $rowset;
    }

    public function todoslosclientes() {
        $query = $this->select()
                ->from(array('u' => 'usuarios'))
                ->where('tipo_usuario=?', 2)
                ->order('visibilidad DESC')
                ->order('nombre ASC')
                ->setIntegrityCheck(false);

        $clientes = $this->fetchAll($query);
        return $clientes;
    }

    public function todoslosadministradores() {
        $query = $this->select()
                ->from(array('u' => 'usuarios'))
                ->where('tipo_usuario=?', 1)
                ->setIntegrityCheck(false);

        $admins = $this->fetchAll($query);
        return $admins;
    }

    public function agregaradministrador($nombre, $apellido, $email, $password, $tipo_usuario, $permiso, $fechaalta, $foto, $visibilidad) {
        
        $data = array('nombre' => $nombre, 
            'apellido' => $apellido,
            'email' => $email, 
            'foto_perfil' => $foto,
            'password' => $password,
            'tipo_usuario' => $tipo_usuario, 'permiso' => $permiso,
            'fecha_alta' => $fechaalta,
            'visibilidad' => $visibilidad,
            'ultimo_acceso'=>$fechaalta);
        $this->insert($data, 0);
    }

    public function editarvisibilidad($id_usuario, $visibilidad) {

        $data = array('visibilidad' => $visibilidad);
        $this->update($data, 'id_usuario = ' . (int) $id_usuario);
    }

    public function vervisibilidad($id_usuario) {

        $select = $this->select();
        $select->from($this->_name, array('visibilidad'))
                ->where('id_usuario=?', $id_usuario);
        $rowset = $this->fetchAll($select);
        foreach ($rowset as $row) {
            $visi = $row->visibilidad;
        }
        return $visi;
    }

    public function datostodosloscliente() {

        $cliente = 2;
        $query = $this->select()
                ->from(array('u' => 'usuarios'))
                ->where('tipo_usuario=?', $cliente)
                ->where('visibilidad=?', 1)
                ->setIntegrityCheck(false);

        $rowset = $this->fetchAll($query);

        return $rowset;
    }

    public function ultimoacceso($id_usuario) {

        $ultimo = new Zend_Db_Expr('NOW()');

        $data = array('ultimo_acceso' => $ultimo);
        $this->update($data, 'id_usuario = ' . (int) $id_usuario);
    }

}

