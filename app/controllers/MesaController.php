<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $numero = $parametros['numero'];

        // Creamos la mesa
        $mesa = new Mesa();
        $mesa->numero = $numero;
        $mesa->id_estado = 1;

        if (Mesa::verificaSiExiste($numero) == false) {
            if ($mesa->crearMesa() != false) {
                $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
            } else {
                $payload = json_encode(array("mensaje" => "Error al crear la mesa"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Error la mesa ya existe"));
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
        // Buscamos usuario por nombre
        $mesaNumero = $args['numero'];
        $mesa = Mesa::obtenerMesa($mesaNumero);

        if ($mesa != false) {
            $payload = json_encode($mesa);
        } else {
            $payload = json_encode(array("ERROR" => $mesaNumero . " no existe"));
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
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));

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

        $numero = $parametros['numero'];
        $id_estado = $parametros['id_estado'];
        $id = $args['id'];


        if (Mesa::VerificarEstado($id_estado) != false) {
            if (Mesa::verificarMesa($id)) {
                Mesa::modificarMesa($numero, $id_estado, $id);
                $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
            } else {
                $payload = json_encode(array("mensaje" => "La mesa no exite"));
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

    public function BorrarUno($request, $response, $args)
    {
        $mesaId = $args['id'];


        if (Mesa::verificarMesa($mesaId) != false) {
            Mesa::borrarMesa($mesaId);
            $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));

        } else {

            $payload = json_encode(array("mensaje" => "La mesa no existe"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

}