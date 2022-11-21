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
    public $id_estado;



    public function crearProducto()
    {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO producto (nombre, codigo_producto, precio, id_tipo , id_estado) VALUES (:nombre, :codigo_producto, :precio, :id_tipo, :id_estado)");
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':codigo_producto', $this->codigo_producto, PDO::PARAM_STR); //posible problema string
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $consulta->bindValue(':id_tipo', $this->id_tipo, PDO::PARAM_INT);
            $consulta->bindValue(':id_estado', $this->id_estado, PDO::PARAM_INT);
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
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, codigo_producto, precio, id_tipo, tiempo_preparacion, id_estado FROM producto");
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
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, codigo_producto, precio, id_tipo, tiempo_preparacion, id_estado FROM producto WHERE nombre = :nombre");
            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->execute();

            $retorno = $consulta->fetchObject('Producto');
        } catch (\Throwable $th) {
            $retorno = $th->getMessage();
        } finally {
            return $retorno;
        }

    }


    public static function obtenerTodosPorEstado($id_estado, $id_tipo_producto)
    {
        $retorno = '';
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, codigo_producto, precio, id_tipo, tiempo_preparacion, id_estado FROM producto WHERE id_estado = :id_estado AND id_tipo = :id_tipo");
            $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
            $consulta->bindValue(':id_tipo', $id_tipo_producto, PDO::PARAM_INT);
            $consulta->execute();

            $retorno = $consulta->fetchAll(PDO::FETCH_CLASS,'Producto');
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

    // funcion encargada de verificar que el producto exista y que tenga un estado de "ingreso a cocina"
    public static function verificarProductoEstadoIngreso($id, $id_tipo_producto)
    {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre, codigo_producto, precio, id_tipo, tiempo_preparacion FROM producto WHERE id = :id AND id_estado = 3 AND id_tipo= :id_tipo");
            $consulta->bindValue(':id', intval($id), PDO::PARAM_INT);
            $consulta->bindValue(':id_tipo', $id_tipo_producto, PDO::PARAM_INT);
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

    // funcion encargada de verificar que el producto exista y que tenga un estado de "En preparacion"
    public static function verificarProductoEstadoPreparacion($id, $id_tipo_producto)
    {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre, codigo_producto, precio, id_tipo, tiempo_preparacion FROM producto WHERE id = :id AND id_estado = 1 AND id_tipo= :id_tipo");
            $consulta->bindValue(':id', intval($id), PDO::PARAM_INT);
            $consulta->bindValue(':id_tipo', $id_tipo_producto, PDO::PARAM_INT);
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



    public static function modificarProducto($nombre, $codigo_producto, $precio, $tiempo_preparacion,$id_estado ,$id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE producto SET nombre = :nombre, codigo_producto = :codigo_producto, precio = :precio, tiempo_preparacion = :tiempo_preparacion, id_estado = :id_estado WHERE id = :id");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':codigo_producto', $codigo_producto, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $precio, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_preparacion', $tiempo_preparacion, PDO::PARAM_INT);
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
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


    //metodo encargado de actualziar el estado y el tiempo de finalizacion de un producto
    // este metodo sera para el punto 3.2 donde cada tipo de empleado le otorga un tiempo y estado al producto
    public static function actualizarEstado($id_estado, $tiempo_finalizacion, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE producto SET id_estado = :id_estado, tiempo_preparacion = :tiempo_preparacion WHERE id = :id");
        $consulta->bindValue(':tiempo_preparacion', $tiempo_finalizacion, PDO::PARAM_INT);
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    //actualiza unicamente el estado del producto
    public static function actualizarEstadoAListo($id_estado, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE producto SET id_estado = :id_estado WHERE id = :id");
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

}