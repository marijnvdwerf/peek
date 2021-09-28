<?php

namespace Marijnvdwerf\Peek;

interface ArticleRepository
{
    /**
     * @return Article[]
     */
    public function all(): array;

    public function find($id): ?Article;
}
