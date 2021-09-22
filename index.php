<?php

use App\Authorization;
use App\AuthorizationException;
use App\Database;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;

require __DIR__. '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$twig = new TwigEnvironment($loader);
$app = AppFactory::create();
$app->addBodyParsingMiddleware(); // $_POST

$config = include_once 'config/database.php';

$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];

$database = new Database($dsn, $username, $password);
$authorization = new Authorization($database);

$app->get('/',function(ServerRequestInterface $request, ResponseInterface $response ) use ($twig) {
    $body = $twig->render('index.twig');
    $response->getBody()->write($body);
   return $response;
});
$app->get('/login', function (ServerRequestInterface $request, ResponseInterface $response) use ($twig) {
    $body = $twig->render('login.twig');
    $response->getBody()->write($body);
    return $response;
});
$app->get('/register', function (ServerRequestInterface $request, ResponseInterface $response) use ($twig) {
    $body = $twig->render('register.twig');
    $response->getBody()->write($body);
    return $response;
});
$app->get('/logout', function (ServerRequestInterface $request, ResponseInterface $response) use ($twig) {
    $body = $twig->render('logout.twig');
    $response->getBody()->write($body);
    return $response;
});

$app->post('/login-post', function (ServerRequestInterface $request, ResponseInterface $response) {
    $response->getBody()->write('login');
    return $response;
});
$app->post('/register-post', function (ServerRequestInterface $request, ResponseInterface $response) use ($authorization) {
    $params = (array) $request->getParsedBody();

  //  var_dump($params);
    try{
        $authorization->register($params);
    }
    catch(AuthorizationException $exception){
        return $response->withHeader('Location','/registor')
        ->withStatus(302);
    }


    return $response->withHeader('Location', '/')
    ->withStatus(302);






    //$response->getBody()->write('Данные получены');
    return $response;
});

$app->run();