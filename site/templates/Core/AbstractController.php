<?php

namespace App\Core;

use ProcessWire\Page;
use ProcessWire\PageArray;
use ProcessWire\Pages;
use ProcessWire\ProcessWire;
use ProcessWire\Template;
use \Twig\Environment;

class AbstractController
{
    /** @var ProcessWire|null $wire */
    private $wire;

    /** @var Environment $twig */
    private $twig;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->wire = ProcessWire::getCurrentInstance();

        $this->twig = new TwigRenderer();
    }

    /**
     * @return Page
     */
    public function page(): Page
    {
        return $this->wire->page;
    }

    /**
     * @return Pages
     */
    public function pages(): Pages
    {
        return $this->wire->pages;
    }

    public function template(): Template
    {
        return $this->wire->page->template;
    }

    /**
     * @param string $view
     * @param array $params
     */
    public function render(string $view, array $params)
    {
        return $this->twig->render($view, $params);
    }
}