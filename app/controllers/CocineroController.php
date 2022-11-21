<?php
require_once './interfaces/IApiUsable.php';

class CocineroController
{
    public function TraerTodos($request, $response, $args)
    {
        $id_tipo_comida = 1; // productos de tipo COMIDA
        $id_estado = 3; // productos entrados a cocina

        $lista = Producto::obtenerTodosPorEstado($id_estado, $id_tipo_comida);
        if (count($lista) > 0 || $lista != false) {

            $payload = json_encode(array("lista de comidas pendientes" => $lista));
        } else {
            $payload = json_encode(array("Mensaje" => "No hay productos del tipo COMIDA PENDIENTES"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

    public function TraerTodosEnPreparacion($request, $response, $args)
    {
        $id_tipo_comida = 1; // productos de tipo COMIDA
        $id_estado = 1;// productos en Preparacion
        
        $lista = Producto::obtenerTodosPorEstado($id_estado, $id_tipo_comida);
        if(count($lista) > 0 || $lista != false){

            $payload = json_encode(array("lista comidas en preparacion" => $lista));
        }else{
            $payload = json_encode(array("Mensaje" => "No hay productos del tipo COMIDA en PREPARACION"));
        }

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
        if (!isset($parametros['id_estado']) || !isset($parametros['tiempo_finalizacion'])) {

            $payload = "Faltan Campos";
            $response->getBody()->write($payload);
            return $response
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $id_estado = $parametros['id_estado'];
        $tiempo_finalizacion = $parametros['tiempo_finalizacion'];
        $id = $args['idProducto'];
        $tipo_producto = 1;//tipo de producto comida
        if (Producto::verificarProductoEstadoIngreso($id, $tipo_producto)) {
            Producto::actualizarEstado($id_estado, $tiempo_finalizacion, $id);
            $payload = json_encode(array("mensaje" => "Estado Producto Actualizado"));
        } else {
            $payload = json_encode(array("mensaje" => "Producto no existe o ya fue entregado"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

    public function ModificarEstado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        if (!isset($parametros['id_estado'])) {

            $payload = "Faltan Campos";
            $response->getBody()->write($payload);
            return $response
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }
        $id_estado = $parametros['id_estado'];
        $id = $args['idProducto'];
        $tipo_producto = 1;//tipo de producto comida
        if (Producto::verificarProductoEstadoPreparacion($id, $tipo_producto)) {
            Producto::actualizarEstadoAListo($id_estado, $id);
            $payload = json_encode(array("mensaje" => "Estado Producto Actualizado"));
        } else {
            $payload = json_encode(array("mensaje" => "Producto no existe o ya fue entregado"));
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