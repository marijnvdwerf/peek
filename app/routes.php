<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $articleRepo = new \Marijnvdwerf\Peek\ArticleRepository();
        $articles = $articleRepo->all();
        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'index.twig', [
            'articles' => $articles
        ]);
    });
};
