<?php
require_once './models/Pedido.php';

class PedidoController extends Pedido  
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombreCliente = $parametros['nombreCliente'];

        // Creamos la mesa
        $pedido = new Pedido();
        $pedido->id_estado = 1;
        $pedido->codigo_pedido = uniqid();
        $pedido->nombre_cliente = $nombreCliente;
        $pedido->fecha_creacion = date("Y-m-d");

        
        if ($pedido->crearPedido() != false) 
        {
                $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
        } else 
        {
            $payload = json_encode(array("mensaje" => "Error al crear el pedido"));
        }
        
        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos pedido por codigo
        $codigoPedido = $args['codigoPedido'];
        $pedido = Pedido::obtenerPedido($codigoPedido);

        if ($pedido != false) {
            $payload = json_encode($pedido);
        } else {
            $payload = json_encode(array("ERROR" => $codigoPedido . " no existe"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedido" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_estado = $parametros['id_estado'];
        $nombre_cliente = $parametros['nombre_cliente'];
        $id = $args['id'];


        if (Pedido::VerificarEstado($id_estado) != false) {
            if (Pedido::verificarPedido($id)) {
                Pedido::modificarPedido($nombre_cliente, $id_estado, $id);
                $payload = json_encode(array("mensaje" => "Pedido modificada con exito"));
            } else {
                $payload = json_encode(array("mensaje" => "El pedido no exite"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "El estado es invalido"));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }


    public function CargarProductos($request, $response, $args){
        $payload = json_encode(array("Error" => "Error al cargar los productos"));
        
        if(isset($request->getParsedBody()['listaProductos']) && Pedido::verificarPedido($args['id'])){

            
            $productos = $request->getParsedBody()['listaProductos'];

            //le paso el id del pedido y el array de prodyctos que recibo por raw de postman
            if(Pedido::CargarProductosPedido($args['id'] ,$productos)){
                
                $payload = json_encode(array("OK" => "Productos Cargados con exito"));
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