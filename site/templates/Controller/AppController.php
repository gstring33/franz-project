<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Services\BlockServices;

class AppController extends AbstractController
{
   /** @var BlockServices  */
    private $blockServices;

    /**
     * AppController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->blockServices = new BlockServices();
    }

    public function index()
    {
        $blocks = $this->blockServices->getBlocks($this->page());
        $blocksViews = [];
        if (count($blocks) > 0) {
            foreach ($blocks as $blockname => $block) {
                $blocksViews[] = $this->render('@blocks/' . $blockname . '.html.twig', [
                    'block' => $block
                ]);
            }
        }

        $home = $this->pages()->get('template=home');
        $current = $this->page()->title;
        $navigation = [
            'home' => $home,
            'items' => $home->children,
            'current' => $current
        ];

        echo $this->render('@content/home.html.twig', [
            'blocks' => $blocksViews,
            'navigation' => $navigation
        ]);
    }
}