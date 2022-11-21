<?php
require_once './models/Comanda.php';

class ComandaController extends Comanda
{

    public function AsociarPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $header = $request->getHeaders();
        $token = explode(" ", $header["Authorization"][0])[1];


        if(!isset($parametros['id_pedido']) || !isset($parametros['id_mesa'])){
            $response->getBody()->write("Ingrese el id del pedido y el id de la mesa");
            
            return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );

        }
        $id_pedido = $parametros['id_pedido'];
        $id_mesa = $parametros['id_mesa'];

        // Creamos la mesa
        $comanda = new Comanda();
        $comanda->id_pedido = $id_pedido;
        $comanda->id_mesa = $id_mesa;

        $claims = AutentificadorJWT::ObtenerPayLoad($token);

        $claim = $claims->data->id_usuario;

        //le paso el id del empleado que tomo el pedido
        $comanda->id_empleado = $claim;

        
        if (Mesa::verificaSiExiste($id_mesa) && Pedido::verificarPedido($id_pedido) != false) {
            if ($comanda->crearComanda() != false) {
                if(Mesa::actualizarEstado($id_mesa,2) && Pedido::actualizarEstado($id_pedido, 2))//id de la mesa, estado 2 = Con cliente esperando pedido || pedido lo mismo 1 en preparacion, 2 listo para servir, 3 entregado
                {
                    $payload = json_encode(array("mensaje" => "Pedido y Mesa asociados con exito"));
                }else{
                    $payload = json_encode(array("mensaje" => "Error actualizar estado mesa"));
                }
            } else {
                $payload = json_encode(array("mensaje" => "La mesa se encuentra ocupada"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Error al asociar la mesa con el pedido"));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }
}

?>