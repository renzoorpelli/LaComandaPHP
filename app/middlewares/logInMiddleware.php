<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Usuario.php';



class logInMiddleware
{

    /**
     * Middleware encargado de verificar al momento de iniciar sesion que el usuario ingrese todos los datos, 
     * de ser valido, pasara al UsuarioController/Login
     
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $reponse = new Response();

        $parametros = $request->getParsedBody();

        if(isset($parametros["clave"]) && isset($parametros["nombre"]))
        {
            if($parametros["clave"] != "" && $parametros["nombre"] != ""){

                $reponse = $handler->handle($request); // llama al controllador
            }else{
                $reponse->getBody()->write("Error, hay campos vacios");
            }
        }else{
            $reponse->getBody()->write("No mandaste todos los campos");
        }

        return $reponse;

    }
}