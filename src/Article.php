<?php

namespace Marijnvdwerf\Peek;

use stdClass;

class Article
{
    public string $title = '';
    public ?string $description = null;
    public string $article_number = '';
    public float $price = 0;
    public ?float $price_campaign = null;
    public string $currency = '';
    public bool $in_stock = false;
    public string $category = '';
    public string $url = '';

    public static function init(stdClass $json)
    {
        $article = new self();
        foreach ($article as $key => $value) {
            $article->$key = $json->$key;
        }
        return $article;
    }
}
