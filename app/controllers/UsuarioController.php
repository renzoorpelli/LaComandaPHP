<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $usuario = $parametros['nombre'];
    $clave = $parametros['clave'];
    $id_rol = $parametros['id_rol'];

    // Creamos el usuario
    $usr = new Usuario();
    $usr->nombre = $usuario;
    $usr->clave = $clave;

    //si existe el rol, se lo asigno, caso contrario tiene rol por default
    if (Usuario::verificarRol($id_rol)) {
      $usr->id_rol = $id_rol;
    } else {
      $usr->id_rol = 2;
    }

    if ($usr->crearUsuario() != false) {
      $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
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
    $usr = $args['usuario'];
    $usuario = Usuario::obtenerUsuario($usr);

    if ($usuario != false) {
      $payload = json_encode($usuario);
    } else {
      $payload = json_encode(array("ERROR" => $usr . " no existe"));
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
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuarios" => $lista));

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
    $clave = $parametros['clave'];
    $id = $args['id'];

    if (Usuario::verificarUsuario($id)) {
      Usuario::modificarUsuario($nombre, $clave, $id);
      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "Usuario no exite"));
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
    $usuarioId = $args['id'];


    if (Usuario::verificarUsuario($usuarioId) != false) {
      Usuario::borrarUsuario($usuarioId);
      $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    } else {

      $payload = json_encode(array("mensaje" => "El usuario no existe"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }

  public function Login($request, $response)
  {

    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    $clave = $parametros['clave'];

    $usuario = Usuario::verificarDatos($nombre, $clave);

    if ($usuario == 1) {

      $usarioAllData = Usuario::obtenerUsuario($nombre);

      $datos = array('usuario' => $nombre, 'role' => "socio", 'id_usuario' => $usarioAllData->id);

      $token = AutentificadorJWT::CrearToken($datos);

      $payload = json_encode(array('OK' => $token));

      $response->getBody()->write($payload);

    } else if ($usuario == 3) {
      $response->getBody()->write("El usuario no existe");
    } else {

      $usarioAllData = Usuario::obtenerUsuario($nombre);

      $datos = array('usuario' => $nombre, 'role' => $usuario, "id_usuario" => $usarioAllData->id);

      $token = AutentificadorJWT::CrearToken($datos);

      $payload = json_encode(array('OK' => $token));

      $response->getBody()->write($payload);

    }


    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }



  public function ObtenerTodosCSV($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode($lista);


    header('Content-Type: application/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=usuarios.csv');
    ob_end_clean();

    //creo el csv con los datos
    if(!file_exists('./usuarios.csv')){
      $data = json_decode($payload, true);
      $fp = fopen("usuarios.csv", 'w');
      foreach ($data as $row) {
        fputcsv($fp, $row);
      }
      fclose($fp);
    }
    readfile('./usuarios.csv');
    //ob_flush();

    return $response
      ->withHeader(
        'Content-Type',
        'application/csv'
      );
  }


  public function CargarDatosDesdeCSV($request, $response, $args){



    
  }
}