<?php

use Marijnvdwerf\Peek\Article;
use Marijnvdwerf\Peek\SearchEngine;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    private function createArticle($title, $description = null): Article
    {
        $article = new Article();
        $article->title = $title;
        $article->description = $description;

        return $article;
    }

    private function createArticles(): array
    {
        return [
            $this->createArticle('Razer BlackWidow V3, gaming-tangentbord'),
            $this->createArticle('Razer Blade 15'),
            $this->createArticle('Google Chromecast', 'Streama från mobilen till TV:n')
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

    public function testMultipleTokens()
    {
        $engine = new SearchEngine($this->createArticles());

        $results = $engine->search('Razer', 'V3');
        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertInstanceOf(Article::class, $results[0]);
        $this->assertEquals("Razer BlackWidow V3, gaming-tangentbord", $results[0]->title);
    }

    public function testSearchesDescription()
    {
        $engine = new SearchEngine($this->createArticles());

        $results = $engine->search('Stream');
        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertInstanceOf(Article::class, $results[0]);
        $this->assertEquals("Google Chromecast", $results[0]->title);
    }
}
