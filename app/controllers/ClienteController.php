<?php

class ClienteController
{
    public function TraerTiempoPedido($request, $response, $args)
    {
        if (!isset($request->getQueryParams()['codigo_mesa']) && !isset($request->getQueryParams()['codigo_pedido'])) {
            $response->getBody()->write("faltan agregar parametros");
            return $response
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }
        $codigo_mesa = $request->getQueryParams()['codigo_mesa'];
        $codigo_pedido = $request->getQueryParams()['codigo_pedido'];
        $pedido = Pedido::obtenerPedido($codigo_pedido);
        $payload = "";

        if($pedido != false && Mesa::verificaSiExiste($codigo_mesa)){
            $mesa = Mesa::obtenerMesa($codigo_mesa);
            if(Comanda::obtenerComanda($pedido->id, $mesa->id )){

                $tiempo = Pedido::setearTiempoPreparacionPedido($pedido->id);

                $payload = json_encode(array("Tiempo de espera" => $tiempo . " minutos"));

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


?>