<?php

use Marijnvdwerf\Peek\Article;
use Marijnvdwerf\Peek\SearchEngine;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testCalculateDiscount() {
        $article = new Article();
        $article->price = 100;
        $article->price_campaign = 80;

        $this->assertEqualsWithDelta(-0.2, $article->getDiscount(), 0.000000001);
    }

    public function testCalculateNoDiscount() {
        $article = new Article();
        $article->price = 100;

        $this->assertSame(null, $article->getDiscount());
    }
}
