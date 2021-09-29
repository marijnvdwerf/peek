<?php
declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface;
use Marijnvdwerf\Peek\Article;
use Marijnvdwerf\Peek\ArticleRepository;
use Marijnvdwerf\Peek\SearchEngine;
use Slim\App;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $articleRepo = $this->get(ArticleRepository::class);
        $articles = $articleRepo->all();
        $articles = array_filter($articles, fn(Article $article) => $article->getDiscount());
        usort($articles, function(Article$lhs, Article $rhs) {
            return abs($rhs->getDiscount()) <=> abs($lhs->getDiscount());
        });
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
        $engine = new SearchEngine($articleRepo->all());
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

    $app->get('/products/{id}/media/1', function (Request $request, Response $response, array $args) {
        $articleRepo = $this->get(ArticleRepository::class);

        // Only allow valid products. This should also stop the filesystem from being browsed
        $article = $articleRepo->find($args['id']);
        if (!$article) {
            throw new HttpNotFoundException($request);
        }

        $filename = ROOT . '/cache/images/' . $article->article_number . '.jpg';
        if (!file_exists($filename)) {
            throw new HttpNotFoundException($request);
        }

        $img = imagecreatefromstring(file_get_contents($filename));
        if ($img === false) {
            throw new HttpInternalServerErrorException($request);
        }


        $sourceW = imagesx($img);
        $sourceH = imagesy($img);
        $sourceMax = max($sourceW, $sourceH);

        // Center resized image
        $thumbnailSize = 300;
        $scale = $thumbnailSize / $sourceMax;
        $destW = (int)round($sourceW * $scale);
        $destH = (int)round($sourceH * $scale);
        $destX = (int)(($thumbnailSize - $destW) / 2);
        $destY = (int)(($thumbnailSize - $destH) / 2);

        $img2 = imagecreatetruecolor($thumbnailSize, $thumbnailSize);
        imagefill($img2, 0, 0, 0xFFFFFF);
        imagecopyresampled($img2, $img, $destX, $destY, 0, 0, $destW, $destH, $sourceW, $sourceH);
        ob_start();
        imagejpeg($img2, quality: 80);
        $imgBin = ob_get_clean();

        $response->getBody()->write($imgBin);
        return $response
            ->withHeader('Cache-Control', 'max-age=2592000, public') // 30 days
            ->withHeader('Content-Type', 'image/jpeg');
    });
};
