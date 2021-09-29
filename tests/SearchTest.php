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

    public function testSimpleResult()
    {
        $engine = new SearchEngine($this->createArticles());

        $results = $engine->search('Google');
        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertInstanceOf(Article::class, $results[0]);
        $this->assertEquals("Google Chromecast", $results[0]->title);
    }

    public function testCaseSensitiveSearch()
    {
        $engine = new SearchEngine($this->createArticles());

        $results = $engine->search('gOOGLE');
        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertInstanceOf(Article::class, $results[0]);
        $this->assertEquals("Google Chromecast", $results[0]->title);
    }
}
