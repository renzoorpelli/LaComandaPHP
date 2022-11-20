<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $codigo_producto = $parametros['codigo_producto'];
        $precio = $parametros['precio'];
        $id_tipo = $parametros['id_tipo'];
        $tiempo_preparacion = $parametros['tiempo_preparacion'];

        // Creamos el producto
        $prod = new Producto();
        $prod->nombre = $nombre;
        $prod->codigo_producto = $codigo_producto;
        $prod->precio = $precio;
        $prod->id_tipo = $id_tipo;
        $prod->tiempo_preparacion = $tiempo_preparacion;

        //si existe el tipo, se lo asigno, caso contrario tiene rol por default
        if (Producto::verificarTipoProducto($id_tipo)) {
            $prod->id_tipo = $id_tipo;
        } else {
            $prod->id_tipo = 1;
        }

        if ($prod->crearProducto() != false) {
            $payload = json_encode(array("mensaje" => "Producto creado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error al crear el usuario"));
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
        $prod = $args['producto'];
        $usuario = Producto::obtenerProducto($prod);

        if ($usuario != false) {
            $payload = json_encode($usuario);
        } else {
            $payload = json_encode(array("ERROR" => $prod . " no existe"));
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
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

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

        $nombre = $parametros['nombre'];
        $codigo_producto = $parametros['codigo_producto'];
        $precio = $parametros['precio'];
        $tiempo_preparacion = $parametros['tiempo_preparacion'];
        $id = $args['id'];

        if (Producto::verificarProducto($id)) {
            Producto::modificarProducto($nombre, $codigo_producto, $precio, $tiempo_preparacion ,$id);
            $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Producto no exite"));
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
        $productoId = $args['id'];


        if (Producto::verificarProducto($productoId) != false) {
            Producto::borrarProducto($productoId);
            $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        } else {

            $payload = json_encode(array("mensaje" => "El Producto no existe"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

}