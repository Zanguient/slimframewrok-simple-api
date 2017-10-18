<?php

#ini_set('error_reporting', E_ALL);
#error_reporting(-1);

//session_destroy();
//session_start();

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

//$mysqli = new mysqli('192.168.0.13', 'root', 'Agrnvst941142', 'agroweb');
ORM::configure('mysql:host=192.168.0.13;dbname=agroweb');
ORM::configure('username', 'root');
ORM::configure('password', 'Agrnvst941142');

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => 'templates',
));


//$app->add(new \Slim\Middleware\SessionCookie(array('secret' => 'myappsecret')));
//$authenticate = function ($app) {
//    return function () use ($app) {
//       if (!isset($_SESSION['user'])) {
//            $_SESSION['urlRedirect'] = $app->request()->getPathInfo();
//            $app->flash('error', 'Login required');
//            $app->redirect('/login');
//        }
//    };
//};

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

//$app->get('/hello/:name', $authenticate($app), function ($name) use ($app) {
//    echo "Helloss, $name";
//    echo BASEDIR.'\\talhao\\query_by_plot.sql';
//});

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



// Rota do Index
require_once 'routes/index/indexRoute.php';










$app->get('/json', function() use ($app){

    // Implementação simples de um cache por dia.
    // Descrição: se o cache for do mesmo dia, exite o arquivo, caso contrario
    date_default_timezone_set("Brazil/East");
    $dtcriacao = @date("YmdHi", filemtime(dirname(__FILE__).'/cache.json') );
    $dthoje = date("YmdHi");

    // Caso o cache tenha menos de 10 minutos, exite o cache
    if (file_exists(dirname(__FILE__).'/cache.json') && ($dthoje - $dtcriacao) <= -1) {

        $jsonCached = include(dirname(__FILE__).'/cache.json');

    } else {

        $queryMedia = 'SELECT
            vpsrt.id_safra,
            vpsrt.id_cultura,
            vpsrt.id_talhao,
            vpsrt.id_fazenda,
            t.descr AS talhao,
            tb.descr AS bloco,
            i.nome AS insumo,
            vpsrt.dt_inicial,
            vpsrt.dt_final,
            vpsrt.peso_armazem,
            vpsrt.peso_silobag,
            vpsrt.peso_silosweep,
            vpsrt.peso_limpo,
            vpsrt.area_total,
            vpsrt.area_plantada,
            vpsrt.area_colhida,
            vpsrt.idade,
            vpsrt.media,
            vpsrt.situacao
        FROM
            v_painel_safra_resultado_temp11 vpsrt
            INNER JOIN talhao t
                ON t.id = vpsrt.id_talhao
            INNER JOIN talhao_bloco tb
                ON tb.id = t.id_talhao_bloco
            INNER JOIN insumo i
                ON i.id = vpsrt.id_insumo
        ORDER BY vpsrt.id_safra ';
        $rMedia = result($queryMedia);

        $cacheMedia = array();
            while($rM = $rMedia->fetch_object()) {
            $cacheMedia[] = $rM;
        }

        $query = "  SELECT
                        t.*,
                        f.nome fazenda
                    FROM talhao t
                    INNER JOIN fazenda f
                        ON f.id = t.id_fazenda
                    WHERE t.geojson IS NOT NULL
                    AND t.descr NOT IN ('I12', 'I11', 'H10', 'H09', 'G09', 'J11', 'I10', 'I09', 'J11', 'J10', 'J09', 'J08')
                    ";
        $resultado = result($query);

        $json = array();
        $id = 0;
        while($r = $resultado->fetch_object()) {

            $talhao = new stdClass();
            $talhao->type = 'Feature';
            $talhao->id = $id++;

            $talhao->properties = new stdClass();
            $talhao->properties->id_talhao = $r->id;
            $talhao->properties->id_fazenda = $r->id_fazenda;
            $talhao->properties->fazenda = $r->fazenda;

            $qPlantioTalhao = "SELECT
                    p.id,
                    p.id_plantio,
                    p.id_fazenda,
                    p.id_safra,
                    p.id_cultura,
                    p.area_producao,
                    p.area_semente,
                    p.area_experimento,
                    p.area_total,
                    p.quantidade_arrendamento,
                    p.dt_entrada,
                    p.validado,
                    pt.id id_plantio_talhao,
                    pt.item,
                    pt.id_plantio,
                    pt.id_talhao,
                    pt.id_insumo,
                    i.nome variedade,
                    pt.id_plantio_talhao_finalidade,
                    ptf.descr finalidade,
                    pt.quantidade area_plantada,
                    pt.dt_entrada,
                    vs.descr vsafra,
                    c.descr cultura
                FROM
                    plantio_talhao pt
                    INNER JOIN plantio p
                        ON p.id = pt.id_plantio
                    INNER JOIN plantio_talhao_finalidade ptf
                        ON ptf.id = pt.id_plantio_talhao_finalidade
                    INNER JOIN insumo i
                        ON i.id = pt.id_insumo
                    INNER JOIN v_safra vs
                        ON vs.id = p.id_safra
                    INNER JOIN cultura c
                        ON c.id = p.id_cultura
                WHERE pt.id_talhao = $r->id
                ORDER BY p.id_safra, p.id";

            $rPlantioTalhao = result($qPlantioTalhao);
            $talhao->properties->safras = array();
            while($s = $rPlantioTalhao->fetch_object()) {
                $talhao->properties->safras[] = $s;
            }

            $talhao->properties->area = $talhao->properties->safras[count($talhao->properties->safras)-1]->area_plantada;

            $talhao->properties->variedade = 'N/A';
            $talhao->properties->situacao = 'N/A';
            $talhao->properties->name = $r->descr;
            $talhao->properties->idade = 10;

            $talhao->properties->medias = array();

            foreach ($cacheMedia as $media) {
                if($media->id_talhao == $r->id) {
                    $talhao->properties->medias[] = $media;
                }
            }

            $talhao->properties->bloco = 'BLOCO';
            $talhao->properties->corpadrao = '#'.dechex(rand(0x000000, 0xFFFFFF));

            $talhao->geometry = new stdClass();
            $talhao->geometry->type = 'Polygon';
            $talhao->geometry->coordinates = $r->geojson;

            $json[] = $talhao;
            }

            // transforma as coordenadas em array
            $export = '{ "type": "FeatureCollection", "features":' . str_replace('"coordinates":"[[[', '"coordinates":[[[',
                str_replace(']]]"}}', ']]] }}',
                  json_encode($json)
                )
            ) . '}';

            echo $export;

            //echo var_export($json,true);
            //echo dirname(__FILE__);
            file_put_contents(dirname(__FILE__).'/cache.json', $export);
    }

});

$app->run();
