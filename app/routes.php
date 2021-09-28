<?php
declare(strict_types=1);

use Marijnvdwerf\Peek\ArticleRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $articleRepo = $this->get(ArticleRepository::class);
        $articles = $articleRepo->all();
        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'index.twig', [
            'articles' => $articles
        ]);
    });

    $app->get('/products/{id}', function (Request $request, Response $response, array $args) {
        $articleRepo = $this->get(ArticleRepository::class);

        $article = $articleRepo->find($args['id']);
        if (!$article) {
            throw new HttpNotFoundException($request);
        }

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'detail.twig', [
            'article' => $article
        ]);
    });
};
