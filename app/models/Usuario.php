<?php

require_once "Rol.php";
class Usuario
{
    public $id;
    public $nombre;
    public $clave;
    public $id_rol;

    public function crearUsuario()
    {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (nombre, clave, id_rol) VALUES (:nombre, :clave, :id_rol)");
            $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
            $consulta->bindValue(':id_rol', $this->id_rol, PDO::PARAM_INT);
            $consulta->execute();
            $retorno = $objAccesoDatos->obtenerUltimoId();
        } catch (Throwable $e) {

            $retorno = $e->getMessage();
        } finally {

            return $retorno;
        }

    }

    public static function obtenerTodos()
    {
        $retorno = '';
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave, id_rol FROM usuario");
            $consulta->execute();

            $retorno =  $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
        } catch (\Throwable $th) {
            $retorno = $th->getMessage();
        } finally {

            return $retorno;
        }

    }

    public static function obtenerUsuario($nombre)
    {
        $retorno = '';
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave FROM usuario WHERE nombre = :nombre");
            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->execute();

            $retorno =  $consulta->fetchObject('Usuario');
        } catch (\Throwable $th) {
            $retorno = $th->getMessage();
        } finally {
            return $retorno;
        }

    }

    // metodo encargado de verificar los datos del usuario, si existe y es socio retorna 1, si no existe, retorna 3, si existe pero no es socio, retorna el rol
    public static function verificarDatos($usuario, $clave)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave, id_rol FROM usuario WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $usuario, PDO::PARAM_STR);
        $consulta->execute();
        $retorno = 0;
        $userDataBase = $consulta->fetchObject('Usuario');

        if ($userDataBase != null) {
            if ($userDataBase->nombre == $usuario) {
                if (password_verify($clave, $userDataBase->clave) || $userDataBase->clave == $clave) {
                    if ($userDataBase->id_rol == 1) {
                        $retorno = 1; // si es socio
                    } else {
                        $retorno = self::obtenerRol($userDataBase->id_rol);
                    }
                } else {
                    $retorno = 3; //usuario invalido
                }
            }
        }
        return $retorno;

    }

    public static function obtenerRol($id_rol): string
    {

        $retorno = "";
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre_rol FROM rol WHERE id = :id");
            $consulta->bindValue(':id', $id_rol, PDO::PARAM_INT);
            $consulta->execute();
            $rol = $consulta->fetchObject('Rol');

            $retorno = $rol->nombre_rol;
        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }


    /*
    metodo encargado de verificar si existe rol
    */
    public static function verificarRol($id_rol)
    {

        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre_rol FROM rol WHERE id = :id");
            $consulta->bindValue(':id', $id_rol, PDO::PARAM_INT);
            $consulta->execute();
            $rol = $consulta->fetchObject('Rol');

            if ($rol != false) {
                $retorno = true;
            }

        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }

    public static function verificarUsuario($id)
    {

        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre FROM usuario WHERE id = :id");
            $consulta->bindValue(':id', intval($id), PDO::PARAM_INT);
            $consulta->execute();
            $usuario = $consulta->fetchObject('Usuario');

            if ($usuario != false) {
                $retorno = true;
            }
        } catch (\Throwable $e) {
            $retorno = false;
            var_dump("gola");
        } finally {
            return $retorno;
        }
    }




    public static function modificarUsuario($nombre, $clave, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuario SET nombre = :nombre, clave = :clave WHERE id = :id");
        $claveHash = password_hash($clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuario SET fecha_baja = :fecha_baja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function generarLogs($accion, $usuarioNombre, $rolUsuario){
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logs_empleado (nombre_empleado, fecha_logue, accion) VALUES (:nombre, :fecha_logue, :accion)");
            $consulta->bindValue(':nombre', $usuarioNombre, PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y"));
            $consulta->bindValue(':fecha_logue', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->bindValue(':accion', $accion, PDO::PARAM_INT);
            $consulta->execute();
            $retorno = $objAccesoDatos->obtenerUltimoId();
        } catch (Throwable $e) {
            $retorno = false;
        } finally {

            return $retorno;
        }
    }
}