<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class criptoCheckMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler):Response{
        $reponse = new Response();

        $parametros = $request->getParsedBody();

        if(isset($parametros["nombre"]) && isset($parametros["precio"]) && isset($parametros["nacionalidad"]) 
        )
        {
            if($parametros["nombre"] != ""  && $parametros["nacionalidad"] != "" 
            && $parametros["precio"] != ""){

                $reponse = $handler->handle($request); // llama al controllador
            }else{
                $reponse->getBody()->write("Error hay campos vacios");
            }
        }else{
            $reponse->getBody()->write("Faltan completar campos");
        }

        return $reponse;
    }
}


?>