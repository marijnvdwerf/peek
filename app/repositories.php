<?php

use DI\ContainerBuilder;
use Marijnvdwerf\Peek\ArticleRepository;
use Marijnvdwerf\Peek\JsonArticleRepository;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        ArticleRepository::class => \DI\autowire(JsonArticleRepository::class),
    ]);
};
