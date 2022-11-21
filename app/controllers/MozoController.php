<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MozoController  
{
    public function ChequearPedidos($request, $response, $args)
    {
        $payload = "Error al Chequear pedidos";

        
        
        

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

}


?>

