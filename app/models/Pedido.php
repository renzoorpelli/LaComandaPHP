<?php
use JsonSchema\Constraints\Constraint;

require_once "ProductoPedido.php";


class Pedido
{
    public $id;
    public $nombre_cliente;
    public $id_estado;
    public $codigo_pedido;
    public $tiempo_finalizacion;
    public $total_pedido;
    public $fecha_creacion;

    public function crearPedido()
    {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (nombre_cliente, id_estado, codigo_pedido, fecha_creacion) VALUES (:nombre_cliente, :id_estado, :codigo_pedido, :fecha_creacion)");
            $consulta->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
            $consulta->bindValue(':id_estado', $this->id_estado, PDO::PARAM_INT);
            $consulta->bindValue(':codigo_pedido', $this->codigo_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':fecha_creacion', $this->fecha_creacion, PDO::PARAM_INT);
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
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre_cliente, id_estado, codigo_pedido, tiempo_finalizacion, total_pedido, fecha_creacion FROM pedido");
            $consulta->execute();

            $retorno = $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        } catch (\Throwable $th) {
            $retorno = $th->getMessage();
        } finally {

            return $retorno;
        }

    }

    public static function obtenerPedido($codigo_pedido)
    {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo_pedido, id_estado, fecha_creacion FROM pedido WHERE codigo_pedido = :codigo_pedido");
            $consulta->bindValue(':codigo_pedido', $codigo_pedido, PDO::PARAM_STR);
            $consulta->execute();
            
            $retorno = $consulta->fetchObject('Pedido');
            
        } catch (\Throwable $th) {
            return false;
        } finally {
            return $retorno;
        }

    }


    public static function obtenerPedidoId($id_pedido)
    {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo_pedido, id_estado, fecha_creacion, total_pedido FROM pedido WHERE id = :id_pedido");
            $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
            $consulta->execute();
            
            $retorno = $consulta->fetchObject('Pedido');
            
        } catch (\Throwable $th) {
            return false;
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

    public static function verificarPedido($id)
    {

        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo_pedido, id_estado FROM pedido WHERE id = :id");
            $consulta->bindValue(':id', intval($id), PDO::PARAM_INT);
            $consulta->execute();
            $producto = $consulta->fetchObject('Pedido');

            if ($producto != false) {
                $retorno = true;
            }
        } catch (\Throwable $e) {
            $retorno = $e->getMessage();
        } finally {
            return $retorno;
        }
    }

    public static function modificarPedido($nombre_cliente, $id_estado, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET nombre_cliente = :nombre_cliente, id_estado = :id_estado WHERE id = :id");
        $consulta->bindValue(':nombre_cliente', $nombre_cliente, PDO::PARAM_INT);
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }


    //funcion encargada de actualizar el monto final (precio) de un pedido
    public static function modificarMontoPedido($id_pedido, $total_pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET total_pedido = :total_pedido WHERE id = :id_pedido");
        $consulta->bindValue(':total_pedido', $total_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $consulta->execute();
    }

    //funcion encargada de actualizar el tiempo final de preaparacion de un pedido
    public static function modificarTiempoPedido($id_pedido, $tiempo_preparacion)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET tiempo_finalizacion = :tiempo_preparacion WHERE id = :id_pedido");
        $consulta->bindValue(':tiempo_preparacion', $tiempo_preparacion, PDO::PARAM_INT);
        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $consulta->execute();
    }

    //funcion encargada de modificar el estado de un pedido
    public static function modificarEstadoPedido($id_pedido, $id_estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET id_estado = :id_estado WHERE id = :id_pedido");
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $consulta->execute();
    }




    //metodo encargado de realizar la carga de productos relacionados a un pedido
    public static function CargarProductosPedido($id_pedido, $productos){

        $producto_pedido = new ProductoPedido();
        $retorno = false;
        $producto = new Producto();
        foreach($productos as $item){

            $producto->nombre = $item['nombre'];
            $producto->codigo_producto = $item['codigo_producto'];
            $producto->precio = $item['precio'];
            $producto->id_tipo = $item['id_tipo'];
            $producto->id_estado = 3;


            if(Pedido::verificarPedido($id_pedido)){

                //creo producto y obtengo ultimo id
                $id_producto = $producto->crearProducto();
                if($producto_pedido->CargarUno($id_pedido,$id_producto) != false){
                    $retorno = true;
                }

            }else{
                continue;
            }
        }

        //si carga los productos, puedo actualizar las horas
        if($retorno == true){
            self::setearPrecioFinalPedido($id_pedido);
            //self::setearTiempoPreparacionPedido($id_pedido);

        }


        return $retorno;

    }
    public static function setearPrecioFinalPedido($id_pedido)
    {
        $productos = ProductoPedido::obtenerTodos($id_pedido);
        $precioFinal = 0;

        foreach ($productos as $item) {
            $precioFinal += $item->precio;
        }
        Pedido::modificarMontoPedido($id_pedido, $precioFinal);

        return $precioFinal;
    }

    public static function setearTiempoPreparacionPedido($id_pedido)
    {
        $productos = ProductoPedido::obtenerTodos($id_pedido);
        $tiempoFinal = 0;
        if(count($productos) > 0 || $productos != false){
            foreach ($productos as $item) {
                $tiempoFinal += $item->tiempo_preparacion;
            }
        }
        Pedido::modificarTiempoPedido($id_pedido, $tiempoFinal);

        return $tiempoFinal;
    }

    //metodo encargado de actualizar el estado de un pedido
    public static function actualizarEstado($id_estado, $id_pedido)
    {
        $retorno = false;
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET id_estado = :id_estado WHERE id = :id");
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->execute();
        $retorno = true;
        return $retorno;
    }



}

?>