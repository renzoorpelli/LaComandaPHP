<?php


require_once "./models/DTO/ProductoPedidoDTO.php";

class ProductoPedido
{
    public $id;
    public $id_producto;
    public $id_pedido;


    // metodo encargado de insetar en la tabla intermedia los productos que pertenecen al pedido
    public  function CargarUno($id_pedido, $id_producto){
        $retorno = false;
        try {
            var_dump($id_pedido);
            var_dump($id_producto);
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO producto_pedido (id_pedido, id_producto) VALUES (:id_pedido, :id_producto)");
            $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT); 
            $consulta->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
            $consulta->execute();
            $retorno = $objAccesoDatos->obtenerUltimoId();
           
        } catch (Throwable $e) {

            $retorno = $e->getMessage();
            var_dump($retorno);
           
        } finally {

            return $retorno;
        }
    }

    // metodo encargado de obtener todos los productos que tiene un pedido en especial
    public static function obtenerTodos($id_pedido){
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta
            (
                "SELECT 
                pedido.id,
                pedido.nombre_cliente,
                prod.nombre,
                pp.id_pedido,
                pp.id_producto,
                prod.precio,
                prod.tiempo_preparacion
                FROM producto_pedido pp
                INNER JOIN producto prod
                on prod.id = pp.id_producto
                INNER JOIN pedido pedido
                ON pedido.id = pp.id_pedido
                WHERE pedido.id = :id_pedido"
            );
            $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $consulta->execute();
            $retorno = $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedidoDTO');
        } catch (Throwable $e) {

           
        } finally {

            return $retorno;
        }
    }
}


?>