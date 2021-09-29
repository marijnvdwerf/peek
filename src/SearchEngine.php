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
        if (preg_match('/' . preg_quote($token, '/') . '/iu', $article->title))
            return true;

        if (preg_match('/' . preg_quote($token, '/') . '/iu', $article->description))
            return true;

        return false;
    }

    public function searchForToken($articles, string $token)
    {
        $out = [];

        foreach ($articles as $article) {
            if ($this->matches($article, $token)) {
                $out[] = $article;
            }
        }

        return $out;
    }

    public function search(string ...$tokens)
    {
        $articles = $this->articles;
        foreach ($tokens as $token) {
            $articles = $this->searchForToken($articles, $token);
        }
        return $articles;
    }
}
