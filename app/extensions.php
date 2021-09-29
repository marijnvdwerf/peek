<?php
declare(strict_types=1);

use Marijnvdwerf\Peek\PriceFormatterExtension;
use Slim\Views\Twig;
use Twig\Extension\DebugExtension;

return function (Twig $twig) {
    $twig->addExtension(new DebugExtension());
    $twig->addExtension(new PriceFormatterExtension());
};
