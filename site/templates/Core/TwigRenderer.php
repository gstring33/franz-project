<?php

namespace App\Core;

use ProcessWire\ProcessWire;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

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
        $twig = new Environment($loader, ['debug' => $this->wire->config('twigDebug')]);
        foreach ($this->getFunctions() as $function) {
            $twig->addFunction($function);
        }
        $this->twig = $twig;
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

    /**
     * @return array|TwigFunction[]
     */
    private function getFunctions(): array
    {
        $functions = [
            new TwigFunction('scripts', function($filename) {
                $file = $this->wire->config->isDevEnvironment ? $filename : $filename . '.min';
                return $this->wire->config->urls->get('js') . $file . '.js?c=' . time();
            }),
            new TwigFunction('styles', function($filename) {
                $file = $this->wire->config->isDevEnvironment ? $filename : $filename . '.min';
                return $this->wire->config->urls->get('css') . $file . '.css?c=' . time();
            }),
        ];

        return $functions;
    }
}