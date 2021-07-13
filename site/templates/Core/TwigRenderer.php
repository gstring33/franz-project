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

    /** @var FilesystemLoader */
    private $loader;

    public function __construct()
    {
        $this->wire = ProcessWire::getCurrentInstance();
        $this->loader = new FilesystemLoader($this->wire->config('twigTemplates'));
        $twig = new Environment($this->loader, ['debug' => $this->wire->config('twigDebug')]);
        $this->addNamespaces($this->wire->config('twigTemplateNamespaces'));
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
            new TwigFunction('scripts', function($filename, $forceMinify = false) {
                if ($forceMinify) {
                    return $this->wire->config->urls->get('js') . $filename . '.min.js?c=' . time();
                }
                $file = $this->wire->config->isDevEnvironment ? $filename : $filename . '.min';
                return $this->wire->config->urls->get('js') . $file . '.js?c=' . time();
            }),
            new TwigFunction('styles', function($filename, $forceMinify = false) {
                if ($forceMinify) {
                    return $this->wire->config->urls->get('css') . $filename . '.min.css?c=' . time();
                }
                $file = $this->wire->config->isDevEnvironment ? $filename : $filename . '.min';
                return $this->wire->config->urls->get('css') . $file . '.css?c=' . time();
            }),
            new TwigFunction('getCategories', function($templateName) {
                return $this->wire->pages->find('template=' . $templateName . ',status=hidden');
            }),
            new TwigFunction('getWorkshopsRows', function($overview=false) {
                $totalPerRow = 3;
                $results = $overview ?
                    $this->wire->pages->get('template=workshops')->children('template=workshop,limit=3') :
                    $this->wire->pages->get('template=workshops')->children('template=workshop');
                $rowIndex = 1;
                $rows = [];
                foreach ($results as $workshop) {
                    if (count($rows[$rowIndex]) === $totalPerRow) {
                        $rowIndex++;
                    }
                    $rows[$rowIndex][] = $workshop;
                }

                return [
                    'total' => count($results),
                    'rows' => $rows
                ];
            }),
            new TwigFunction('getNavigation', function() {
                $navigationConf = $this->wire->pages->get('template=navigation');
                $home = $this->wire->pages->get('template=home');
                $navigation = [
                    'icon' => $navigationConf->image->size(300,100),
                    'current' => $this->wire->page->title,
                    'home' => $home,
                    'items' => $home->children()
                ];

                return $navigation;
            }),
            new TwigFunction('getFooter', function() {
                $footerConf = $this->wire->pages->get('template=footer');
                $usefullLinks = $this->wire->pages->get('template=home')->children('display_in_footer=1');
                $services = $this->wire->pages->find('template=service');
                $footer = [
                    'aboutMe' => $footerConf->text,
                    'usefullLinks' => $usefullLinks,
                    'services' => $services
                ];

                return $footer;
            }),
            new TwigFunction('getBreadcrumb', function() {
                $items = explode('/', $this->wire->page->url);
                $sanitizedItems = [
                    ['name' => 'Home', 'link' => '/']
                ];
                foreach ($items as $index => $item) {
                    if ($item === "") {
                        unset($items[$index]);
                    } else {
                       $sanitizedItems[] = [
                           'name' => ucfirst($item),
                           'link' => $this->wire->pages->get('name=' . $item)->url
                       ];
                    }
                }

                return $sanitizedItems;
            })
        ];

        return $functions;
    }

    /**
     * @param array $paths
     * @throws \Twig\Error\LoaderError
     */
    public function addNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace => $path) {
            $this->loader->addPath($path, $namespace);
        }
    }
}