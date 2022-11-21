<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class usuarioCheckMiddleware
{
    /*

        Middleware encargado de verificar el registro de un usuario, si los parametros que recibe son validos
        redirige hacia el controller Register, quien le otorgara el rol de Cliente al usuario.


    */
    public function __invoke(Request $request, RequestHandler $handler):Response{
        $reponse = new Response();

        $parametros = $request->getParsedBody();

        if(isset($parametros["mail"]) && isset($parametros["clave"]))
        {
            if($parametros["mail"] != "" && $parametros["clave"] != ""){

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