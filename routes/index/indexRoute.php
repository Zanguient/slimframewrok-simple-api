<?php

// Define routes
$app->get('/centralmonitoramento', function () use ($app) {
    // Sample log message
    $query = "SELECT
    *
    FROM usuario";

    $resultado = result($query);

    $result = array();
    while($r = $resultado->fetch_object()) {
      $result[] = $r;
    }


    $params = array(
        'noticias' => $result
    );
    // Render index view
    $app->render('geral/monitoramento_geral.html', $params);
});
