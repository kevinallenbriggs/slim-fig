<?php

namespace KevinAllenBriggs\SlimFig;

use GuzzleHttp\Psr7\Request as Request;
use GuzzleHttp\Psr7\Response as Response;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {

    $twigFSLoader = new FilesystemLoader('../templates');
    $twig = new Environment($twigFSLoader, ['cache' => '../var/twig/cache/']);
    $template = $twig->load('images.html.twig');

    $gatherer = new ImageGatherer('images/');

    // sort the images into roughly equal columns
    $columns = [];
    $current_column = 1;
    foreach ($gatherer->collect(0, 500) as $filename) {
        $columns[$current_column][] = $filename;

        if (++$current_column > 3) {
            $current_column = 1;
            continue;
        }
    }

    // render the template
    $response->getBody()->write($template->render(['columns' => $columns]));
    return $response;
});

$app->get('/images/?offset=20&page=4&order=latest', function (Request $req, Response $res, $args) {
    //
});

$app->run();