<?php

require_once "./models/EstadoMesa.php";
class Mesa
{
    public $id;
    public $numero;
    public $id_estado;
    public $fecha_baja;

    public function crearMesa()
    {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesa (numero, id_estado) VALUES (:numero, :id_estado)");
            $consulta->bindValue(':numero', $this->numero, PDO::PARAM_INT);
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
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, numero, id_estado FROM mesa");
            $consulta->execute();

            $retorno = $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        } catch (\Throwable $th) {
            $retorno = $th->getMessage();
        } finally {

            return $retorno;
        }

    }

    public static function obtenerMesa($numero)
    {
        $retorno = '';
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, numero, id_estado FROM mesa WHERE numero = :numero");
            $consulta->bindValue(':numero', $numero, PDO::PARAM_STR);
            $consulta->execute();

            $retorno = $consulta->fetchObject('Mesa');
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
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre_estado FROM estado_mesa WHERE id = :id");
            $consulta->bindValue(':id', $id_tipo, PDO::PARAM_INT);
            $consulta->execute();
            $rol = $consulta->fetchObject('EstadoMesa');

            $retorno = $rol->nombre;
        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }


    /*
    metodo encargado de verificar si existe estado de mesa
    */
    public static function VerificarEstado($id_estado)
    {

        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre_estado FROM estado_mesa WHERE id = :id");
            $consulta->bindValue(':id', $id_estado, PDO::PARAM_INT);
            $consulta->execute();
            $rol = $consulta->fetchObject('EstadoMesa');

            if ($rol != false) {
                $retorno = true;
            }

        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }

    public static function verificarMesa($id)
    {

        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT numero, id_estado FROM mesa WHERE id = :id");
            $consulta->bindValue(':id', intval($id), PDO::PARAM_INT);
            $consulta->execute();
            $producto = $consulta->fetchObject('Mesa');

            if ($producto != false) {
                $retorno = true;
            }
        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }

    public static function verificaSiExiste($numero)
    {

        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT numero, id_estado FROM mesa WHERE id = :numero");
            $consulta->bindValue(':numero', intval($numero), PDO::PARAM_INT);
            $consulta->execute();
            $producto = $consulta->fetchObject('Mesa');

            if ($producto != false) {
                $retorno = true;
            }
        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }



    public static function modificarMesa($numero, $id_estado, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa SET numero = :numero, id_estado = :id_estado WHERE id = :id");
        $consulta->bindValue(':numero', $numero, PDO::PARAM_INT);
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    //metodo encargado de actualizar el estado de una mesa //cerrada, con cliente esperando pedido, con cliente pagando
    public static function actualizarEstado($id_mesa, $id_estado)
    {
        $retorno = false;
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa SET id_estado = :id_estado WHERE id = :id");
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_mesa, PDO::PARAM_INT);
        $consulta->execute();
        $retorno = true;
        return $retorno;
    }

    public static function borrarMesa($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa SET fecha_baja = :fecha_baja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    

}
?>