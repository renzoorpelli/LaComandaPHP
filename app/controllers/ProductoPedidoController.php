<?php
require_once './models/Pedido.php';

class ProductoPedidoController extends ProductoPedido  
{

    public function TraerTodos($request, $response, $args)
    {
        $payload = json_encode(array("Error" =>  "Error al traer los productos por pedido"));
        if(isset($args['idPedido'])){

            if(Pedido::verificarPedido($args['idPedido'])){
                $lista = ProductoPedido::obtenerTodos($args['idPedido']);
                if(count($lista) > 0){
                    $payload = json_encode(array("listaProductoPedido" => $lista));
                }
                else{
                    $payload = json_encode(array("Atencion" => "El pedido no tiene productos"));
                }
            }else{
                $payload = json_encode(array("ERROR" => "El pedido no existe"));
            }
            
        }
        

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

    


    

    

}