<?php

class Producto
{

    public $id;
    public $nombre;
    public $codigo_producto;
    public $precio;
    public $id_tipo;
    public $fecha_baja;
    public $tiempo_preparacion;



    public function crearProducto()
    {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO producto (nombre, codigo_producto, precio, id_tipo , tiempo_preparacion) VALUES (:nombre, :codigo_producto, :precio, :id_tipo, :tiempo_preparacion)");
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':codigo_producto', $this->codigo_producto, PDO::PARAM_STR); //posible problema string
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $consulta->bindValue(':id_tipo', $this->id_tipo, PDO::PARAM_INT);
            $consulta->bindValue(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
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
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, codigo_producto, precio, id_tipo, tiempo_preparacion FROM producto");
            $consulta->execute();

            $retorno = $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
        } catch (\Throwable $th) {
            $retorno = $th->getMessage();
        } finally {

            return $retorno;
        }

    }

    public static function obtenerProducto($nombre)
    {
        $retorno = '';
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, codigo_producto, precio, id_tipo, tiempo_preparacion FROM producto WHERE nombre = :nombre");
            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->execute();

            $retorno = $consulta->fetchObject('Producto');
        } catch (\Throwable $th) {
            $retorno = $th->getMessage();
        } finally {
            return $retorno;
        }

    }

    public static function obtenerTipoProducto($id_tipo): string
    {

        $retorno = "";
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre_tipo FROM tipo_producto WHERE id = :id");
            $consulta->bindValue(':id', $id_tipo, PDO::PARAM_INT);
            $consulta->execute();
            $rol = $consulta->fetchObject('TipoProducto');

            $retorno = $rol->nombre;
        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }


    /*
    metodo encargado de verificar si existe tipo de producto
    */
    public static function verificarTipoProducto($id_rol)
    {

        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre_tipo FROM rol WHERE id = :id");
            $consulta->bindValue(':id', $id_rol, PDO::PARAM_INT);
            $consulta->execute();
            $rol = $consulta->fetchObject('TipoProducto');

            if ($rol != false) {
                $retorno = true;
            }

        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }

    public static function verificarProducto($id)
    {

        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre, codigo_producto, precio, id_tipo, tiempo_preparacion FROM producto WHERE id = :id");
            $consulta->bindValue(':id', intval($id), PDO::PARAM_INT);
            $consulta->execute();
            $producto = $consulta->fetchObject('Producto');

            if ($producto != false) {
                $retorno = true;
            }
        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }



    public static function modificarProducto($nombre, $codigo_producto, $precio, $tiempo_preparacion, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE producto SET nombre = :nombre, codigo_producto = :codigo_producto, precio = :precio, tiempo_preparacion = :tiempo_preparacion WHERE id = :id");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':codigo_producto', $codigo_producto, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $precio, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_preparacion', $tiempo_preparacion, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE producto SET fecha_baja = :fecha_baja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

}