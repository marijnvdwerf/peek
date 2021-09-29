<?php

namespace Marijnvdwerf\Peek;

class SearchEngine
{
    /** @var Article[] */
    private array $articles;

    public function __construct($articles)
    {
        $this->articles = $articles;
    }

    private function matches(Article $article, string $token): bool
    {
        if (preg_match('/' . preg_quote($token, '/') . '/iu', $article->title, $token))
            return true;

        return false;
    }

    public function search(string $token)
    {
        $out = [];

        foreach ($this->articles as $article) {
            if ($this->matches($article, $token)) {
                $out[] = $article;
            }
        }

        return $out;
    }
}
