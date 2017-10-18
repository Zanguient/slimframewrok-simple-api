<?php

define('SEP', '\\');
define('BASEDIR', dirname(__FILE__).'/app');
define('DUMPQUERY', false);

define('USERSS', 'USER');

require 'vendor/autoload.php';

require 'app/IApiLoginService.php';
require 'app/IApi.php';
require 'app/Idiorm.php';
require 'app/util/Util.php';
require 'app/util/Pivot.php';
require 'app/util/Underscore.php';
require 'app/index/Initial.php';


ORM::configure('mysql:host=xxx;dbname=xxx');
ORM::configure('username', 'xxx');
ORM::configure('password', 'xxx');

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => 'templates',
));

$app->hook('slim.before.dispatch', function() use ($app) { 
   $user = null;
   if (isset($_SESSION['user'])) {
      $user = $_SESSION['user'];
   }
   $app->view()->setData('user', $user);
});


// Create monolog logger and store logger in container as singleton
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('slim-skeleton');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    //'cache' => realpath('templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());



$app->get('/init(/:name)','Initial:index');

// Will be improved in the future
registerController($app,
    'Talhao', array(
        'getIndex|(/:name)',
        'getAll|/:aoww',
        'deletePlot|/:id_safra/:id_talhao',
        'getCostByPlot|/:id_fazenda/:id_cultura/:id_talhao/:id_safra',
        'getPlotDetails|/:id_fazenda/:id_cultura/:id_talhao/:id_safra',
        'getFarmAverage|/:id_fazenda/:id_safra/:id_cultura',
        'putUpdate'
    )
);

registerController($app,
    'Login', array(
        'getIndex|(/:name)',
        'posLogin|',
        'getLogout|(/:session)'
    )
);

registerController($app,
    'OsCampo', array(
        'getIndex|(/:name)',
        'osList|',
        'osItemList|',
        'talhaoList|'
    )
);

registerController($app,
    'OsTipo', array(
        'getIndex|(/:name)',
        'osTipoList|'
    )
);

registerController($app,
    'Fazenda', array(
        'getIndex|(/:name)',
        'fazendaList|'
    )
);

registerController($app,
    'Atividade', array(
        'getIndex|(/:name)',
        'atividadeList|'
    )
);

registerController($app,
    'Safra', array(
        'getIndex|(/:name)',
        'safraList|'
    )
);

registerController($app,
    'Cultura', array(
        'getIndex|(/:name)',
        'culturaList|'
    )
);

$app->run();
