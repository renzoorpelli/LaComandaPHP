<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MozoController
{
    public function VerificarPedido($request, $response, $args)
    {
        $payload = "Error ingrese un numero de pedido";
        $parametros = $request->getParsedBody();

        $contadorProductosListos = 0;
        if (isset($parametros['id_pedido'])) {
            $idPedido = $parametros['id_pedido'];
            if (Pedido::verificarPedido($idPedido)) {

                $productosPedido = ProductoPedido::obtenerTodos($idPedido);
                foreach ($productosPedido as $prod) {

                    if ($prod->id_estado == 2) {
                        $contadorProductosListos++;
                    }
                }
                //si TODOS los productos del pedido tienen el id 2 "LISTO PARA SERVIR", significa que se encuentran listos
                if ($contadorProductosListos == count($productosPedido)) {
                    $payload = json_encode(array("INFORMACION" => "El pedido ID: " . $idPedido . " se encuentra listo para servir"));
                    Pedido::modificarEstadoPedido($idPedido, 2); // 2 = Listo para servir
                } else {
                    $payload = json_encode(array("INFORMACION" => "Faltan productos para finalizar"));
                }


            } else {
                $payload = "El pedido no existe";
            }

        }




        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }


    /** 
     * funcion encargada de cambiar el estado de una mesa a Con clientes comiendo
     */

    public function CambiarEstadoMesa($request, $response, $args)
    {
        $payload = "Error ingrese un numero de pedido";
        $parametros = $request->getParsedBody();

        if (isset($parametros['id_pedido'])) {
            $idPedido = $parametros['id_pedido'];
            if (Pedido::verificarPedido($idPedido)) {
                $pedido = Pedido::obtenerPedidoId($idPedido);
                if ($pedido != false) {
                    if ($pedido->id_estado == 2) {                        
                        $comandaModificar = Comanda::obtenerMesaPedido($idPedido); // la comanda a la cual contiene la mesa que queremso cambiar el estado
                        if ($comandaModificar != false) {
                            $mesaModificar = Mesa::obtenerMesaId($comandaModificar->id_mesa);
                            if ($mesaModificar != false) {
                                Pedido::actualizarEstado(3, $idPedido); //3 = Pedido Entregado 
                                Mesa::actualizarEstado($mesaModificar->id, 3); // 3 = con cliente comiendo
                                $payload = json_encode(array("INFORMACION" => "La Mesa ID: " . $mesaModificar->id . " que tiene el Pedido ID: " . $idPedido . " cambio de estado y se encuentra con cliente comiendo"));
                            }
                        }
                    } else {
                        $payload = json_encode(array("INFORMACION" => "El pedido ID: " . $idPedido . " aun no esta listo o ya fue entregado"));
                    }

                }


            } else {
                $payload = "El pedido no existe";
            }
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

    /** 
     * funcion encargada de mostrar el total de una mesa
     */

    public function CobrarMesa($request, $response, $args)
    {
        $payload = "Error ingrese una mesa valida";

        if (isset($args['idMesa'])) {
            $idMesa = $args['idMesa'];
            if (Mesa::verificarMesa($idMesa)) {
                $comandaModificar = Comanda::obtenerPedidoMesa($idMesa); // la comanda a la cual contiene el pedido el cual queremos saber el total
                if ($comandaModificar != false) {
                    $pedido = Pedido::obtenerPedidoId($comandaModificar->id_pedido);
                    if ($pedido != false) {
                        if ($pedido->id_estado == 3) {
                            $precioFinal = Pedido::setearPrecioFinalPedido($pedido->id);
                            Mesa::actualizarEstado($idMesa, 4); // 4 = con cliente pagando
                            $payload = json_encode(array("INFORMACION" => "La Mesa ID: " . $idMesa . " que tiene el Pedido ID: " . $pedido->id . " Tiene un total a pagar por $" . $precioFinal));
                        } else {
                            $payload = json_encode(array("INFORMACION" => "El pedido ID: " . $pedido->id . " aun no esta listo"));
                        }
                    }
                }else{
                    $payload = json_encode(array("INFORMACION" => "La Mesa ID: " . $idMesa . " aun no esta asociada a ningun pedido"));
                }
            }

        } else {
            $payload = "El pedido no existe";
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

