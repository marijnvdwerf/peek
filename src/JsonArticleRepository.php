<?php

namespace Marijnvdwerf\Peek;

class JsonArticleRepository implements ArticleRepository
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

    public function find($id): ?Article
    {
        foreach ($this->items as $item) {
            if ($item->article_number === $id) {
                return $item;
            }
        }

        return null;
    }
}
