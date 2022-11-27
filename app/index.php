<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

//JWT TOOLS
require_once "./utils/AutentificadorJWT.php";


//MIDDLEWARES
// ROLE MIDDLEWARES
require_once './middlewares/RoleCheckerMiddlewares/socioCheckMiddleware.php';
require_once './middlewares/RoleCheckerMiddlewares/mozoCheckMiddleware.php';
require_once './middlewares/RoleCheckerMiddlewares/bartenderCheckMiddleware.php';
require_once './middlewares/RoleCheckerMiddlewares/cerverceroCheckMiddleware.php';
require_once './middlewares/RoleCheckerMiddlewares/cocineroCheckMiddleware.php';

//JWT HEADER CHECK MIDDLEWARE
require_once './middlewares/jwtCheckerMiddleware.php';

//CREDENTIALS CHECK MIDDLEWARE
require_once './middlewares/logInMiddleware.php';




//CONTROLLERS
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoPedidoController.php';
require_once './controllers/ComandaController.php';

//CONTROLLERS BY ROLE
require_once './controllers/CocineroController.php';
require_once './controllers/BartenderController.php';
// require_once './controllers/SocioController.php';
require_once './controllers/CerveceroController.php';
require_once './controllers/MozoController.php';
require_once './controllers/ClienteController.php';



// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

//solo los socios pueden acceder a las acciones de los usuario
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  //$group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->put('/{id}', \UsuarioController::class . ':ModificarUno');
  $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
  $group->get('/obtenerLista', \UsuarioController::class . ':ObtenerTodosCSV');
})->add(new socioCheckMiddleware())->add(new jwtCheckerMiddleware());


//login, returns jwt with claims
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . ':Login');
})->add(new logInMiddleware());



$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{numero}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno');
  $group->put('/{id}', \MesaController::class . ':ModificarUno');
  $group->delete('/{id}', \MesaController::class . ':BorrarUno');
})->add(new socioCheckMiddleware())->add(new jwtCheckerMiddleware());


$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{producto}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno');
  $group->put('/{id}', \ProductoController::class . ':ModificarUno');
  $group->delete('/{id}', \ProductoController::class . ':BorrarUno');
});


$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{codigoPedido}', \PedidoController::class . ':TraerUno');
  $group->post('[/]', \PedidoController::class . ':CargarUno')->add(new mozoCheckMiddleware())->add(new jwtCheckerMiddleware());
  $group->put('/{id}', \PedidoController::class . ':ModificarUno');
  $group->post('/{id}', \PedidoController::class . ':CargarProductos')->add(new mozoCheckMiddleware())->add(new jwtCheckerMiddleware());

});


$app->group('/productoPedido', function (RouteCollectorProxy $group) {
  $group->get('/{idPedido}', \ProductoPedidoController::class . ':TraerTodos');

});




$app->group('/mozos', function (RouteCollectorProxy $group) {
  $group->get('/{idPedido}', \ProductoPedidoController::class . ':TraerTodos');//pedidos de mozos
  $group->post('[/]', \ComandaController::class . ':AsociarPedido');//asocia la mesa con el pedido. Punto 2
  $group->post('/verificarPedido', \MozoController::class . ':VerificarPedido'); // verifica si el pedido esta listo para servir PUNTO 7
  $group->post('/cambiarEstadoMesa', \MozoController::class . ':CambiarEstadoMesa'); // cambia el estado de la mesa PUNTO 7
  $group->get('/mostarCuenta/{idMesa}', \MozoController::class . ':CobrarMesa'); // mostar Total Cuenta Mesa. Punto 9
})->add(new mozoCheckMiddleware())->add(new jwtCheckerMiddleware());



$app->group('/bartenders', function (RouteCollectorProxy $group) {
  $group->get('[/]', \BartenderController::class . ':TraerTodos');//pedidos de bartender
  $group->put('/{idProducto}', \BartenderController::class . ':ModificarUno');//actualizar estado producto y tiempo finalizacion
  $group->get("/productosEnPreparacion", \BartenderController::class . ':TraerTodosEnPreparacion');
  $group->put("/productosEnPreparacion/{idProducto}", \BartenderController::class . ':ModificarEstado');
})->add(new bartenderCheckMiddleware)->add(new jwtCheckerMiddleware());



$app->group('/cocineros', function (RouteCollectorProxy $group) {
  $group->get('[/]', \CocineroController::class . ':TraerTodos');//pedidos de cocineros
  $group->put('/{idProducto}', \CocineroController::class . ':ModificarUno');//actualizar estado producto y tiempo finalizacion
  $group->get("/productosEnPreparacion", \CocineroController::class . ':TraerTodosEnPreparacion');
  $group->put("/productosEnPreparacion/{idProducto}", \CocineroController::class . ':ModificarEstado');
})->add(new cocineroCheckMiddleware())->add(new jwtCheckerMiddleware());



$app->group('/cerveceros', function (RouteCollectorProxy $group) {
  $group->get('[/]', \CerveceroController::class . ':TraerTodos');//pedidos cerveceros
  $group->put('/{idProducto}', \CerveceroController::class . ':ModificarUno');//actualizar estado producto y tiempo finalizacion
  $group->get("/productosEnPreparacion", \CerveceroController::class . ':TraerTodosEnPreparacion');
  $group->put("/productosEnPreparacion/{idProducto}", \CerveceroController::class . ':ModificarEstado');
})->add(new cerverceroCheckMiddleware())->add(new jwtCheckerMiddleware());





$app->group('/socios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoPedidoController::class . ':TraerTodos');//pueden ver todo. Punto 5
  $group->put('/{idMesa}', \MesaController::class . ':CerrarMesa');

})->add(new socioCheckMiddleware())->add(new jwtCheckerMiddleware());





$app->group('/clientes', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ClienteController::class . ':TraerTiempoPedido');//cliente puede ver su pedido. Punto 4

});


$app->run();
