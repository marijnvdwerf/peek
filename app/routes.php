<?php
declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface;
use Marijnvdwerf\Peek\ArticleRepository;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
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

    $app->get('/search', function (Request $request, Response $response) {
        if (!isset($request->getQueryParams()['q'])) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(StatusCodeInterface::STATUS_FOUND);
        }

        $query = $request->getQueryParams()['q'];
        $tokens = preg_split('/\s+/', $query);
        $tokens = array_filter($tokens);

        if (empty($tokens)) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(StatusCodeInterface::STATUS_FOUND);
        }

        $articleRepo = $this->get(ArticleRepository::class);
        $engine = new \Marijnvdwerf\Peek\SearchEngine($articleRepo->all());
        $articles = $engine->search(...$tokens);
        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'search.twig', [
            'query' => $query,
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
