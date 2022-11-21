<?php

class Comanda
{
    public $id;
    public $id_mesa;
    public $id_pedido;
    public $id_empleado;



    public function crearComanda() {
        $retorno = false;
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO comanda (id_mesa, id_pedido, id_empleado) VALUES (:id_mesa, :id_pedido, :id_empleado)");
            $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
            $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':id_empleado', $this->id_empleado, PDO::PARAM_INT);
            $consulta->execute();
            $retorno = $objAccesoDatos->obtenerUltimoId();
        } catch (Throwable $e) {

            $retorno = $e->getMessage();
        } finally {

            return $retorno;
        }
    }


    //metodo encargado de verificar si existe una comanda referida a un producto y a una mesa
    //metodo encargado de realizar una verficiacion para el punto 4
    public static function obtenerComanda($id_pedido, $id_mesa){
        $retorno = '';
        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
            FROM comanda 
            WHERE id_pedido = :id_pedido 
            AND id_mesa = :id_mesa");

            $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $consulta->execute();
            
            $retorno = $consulta->fetchObject('Comanda');
            if($retorno != false){
                return true;
            }
            
        } catch (\Throwable $th) {
            $retorno = $th->getMessage();
        } finally {
            return $retorno;
        }
    }
}

?>