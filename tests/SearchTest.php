<?php

use Marijnvdwerf\Peek\Article;
use Marijnvdwerf\Peek\SearchEngine;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    private function createArticle($title): Article
    {
        $article = new Article();
        $article->title = $title;

        return $article;
    }

    private function createArticles(): array
    {
        return [
            $this->createArticle('Razer BlackWidow V3, gaming-tangentbord'),
            $this->createArticle('Google Chromecast')
        ];
    }

    public function testNoResultSearch()
    {
        $engine = new SearchEngine($this->createArticles());

        $results = $engine->search('foo');
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }
}
