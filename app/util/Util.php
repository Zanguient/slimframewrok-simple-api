<?php

function result($query = ''){

    $mysqli = new mysqli('192.168.0.13', 'root', 'Agrnvst941142', 'agroweb');

    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') '
                . $mysqli->connect_error);
    }

    $result = $mysqli->query($query) or die($mysqli->error);

    return $result;

}



function registerController($app, $controller, $patterns){

    $dir = strtolower($controller);

    require BASEDIR."/$dir/$controller.php";

    foreach ($patterns as $method) {
        $httpMethod = substr($method, 0, 3);
        $httpMethodName = substr($method, 3, 999);

        $param = (strpos($method, '|') > 0 ? explode('|', $method) : '');
        $p = (count($param) > 1 ? $param[1] : '');

        $method = (count($param)>1 ? $param[0] : $method);

        //echo "<br>app->$httpMethod('$dir/".strtolower($method)."$p',\"$controller:$method\");";
        //echo "--- " . substr($method, 3, 999) . " ---<br>";

        switch ($httpMethod) {
            case 'get':

                //$app->get('/hello/:name', $authenticate($app), function ($name) use ($app) {
                $app->get('/'.$dir.'/'.strtolower($method).$p, "$controller:$method");
                break;

            case 'pos':
                $app->post('/'.$dir.'/'.strtolower($method).$p, "$controller:$method");
                break;

            case 'put':
                $app->put('/'.$dir.'/'.strtolower($method).$p, "$controller:$method");
                break;

            case 'del':
                $app->delete('/'.$dir.'/'.strtolower($method).$p, "$controller:$method");
                break;

            default:
                $app->get('/'.$dir.'/'.strtolower($method).$p, "$controller:$method");
                break;
        }
    }

    ///echo "<br>";

}


function sanitize($str) {
  $str = trim($str);

  if (get_magic_quotes_gpc())
    $str = stripslashes($str);

  return htmlentities(mysql_real_escape_string($str));
}

function array_flatten($array) {
    $return = array();
    foreach ($array as $key => $value) {
        if (is_array($value)){
            $return = array_merge($return, array_flatten($value));
        } else {
            $return[$key] = $value;
        }
    }

    return $return;
}