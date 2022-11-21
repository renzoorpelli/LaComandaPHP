<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
require_once "./utils/AutentificadorJWT.php";

class socioCheckMiddleware{

    /*

        middleware encargado de verificar si el usuario que intenta hacer una peticion 
        es socio, de no ser asi, rechaza la peticion

    */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
            //metodo verify
            //verifica si esta activo, si no se vencio
            //si existe el usuario
            //si tiene la misma firma
        $payload = "";
        $response = new Response();
        $token = "";
        try{
            $header = $request->getHeaderLine("Authorization");
            if($header != null){
                $token = trim(explode("Bearer", $header)[1]);
            }
            $claims = AutentificadorJWT::ObtenerPayLoad($token);
            $claim = $claims->data->role;
            strtolower($claim);
            if($claim == "socio"){
                $response = $handler->handle($request);
            }
            else{
                $payload = json_encode(array("ERROR" => "No estas autorizado"));
                $response->getBody()->write($payload);
            }
        }catch (\Throwable $e) {

            $payload = json_encode(array('error' => $e->getMessage()));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}