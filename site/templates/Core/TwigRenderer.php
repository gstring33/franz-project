<?php

namespace App\Core;

use ProcessWire\ProcessWire;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer
{
    /** @var ProcessWire|null */
    private $wire;

    /** @var Environment */
    private $twig;

    public function __construct()
    {
        $this->wire = ProcessWire::getCurrentInstance();
        $loader = new FilesystemLoader($this->wire->config('twigTemplates'));
        $this->twig = new Environment($loader, ['debug' => $this->wire->config('twigDebug')]);
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params): string
    {
        return $this->twig->render($view, $params);
    }
}