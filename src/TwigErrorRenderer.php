<?php

namespace Marijnvdwerf\Peek;

use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Interfaces\ErrorRendererInterface;
use Slim\Views\Twig;
use Throwable;

class TwigErrorRenderer implements ErrorRendererInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        return $this->twig->fetch('error.twig', [
            'exception' => $exception
        ]);
    }
}
