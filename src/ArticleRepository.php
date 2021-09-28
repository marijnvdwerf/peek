<?php

namespace Marijnvdwerf\Peek;

class ArticleRepository
{

    /**
     * @var Article[]
     */
    private array $items;

    public function __construct()
    {
        $items = json_decode(file_get_contents(ROOT . '/resources/data/articles.json'));

        $this->items = array_map(function ($json) {
            return Article::init($json);
        }, $items);
    }


    /**
     * @return Article[]
     */
    public function all(): array
    {
        return $this->items;
    }
}
