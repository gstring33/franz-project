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
        $this->blockServices = new BlockServices($this->config());
    }

    public function home()
    {
        $blocksViews = $this->getBlocksViews();
        $navigation = $this->getNavigation();

        echo $this->render('@content/home.html.twig', [
            'blocks' => $blocksViews,
            'navigation' => $navigation
        ]);
    }

    public function index()
    {
        $blocksViews = $this->getBlocksViews();
        $navigation = $this->getNavigation();

        echo $this->render('@content/page.html.twig', [
            'blocks' => $blocksViews,
            'navigation' => $navigation
        ]);
    }

    // ----- PRIVATE METHOD ----- //

    /**
     * @return array
     */
    private function getBlocksViews()
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

        return $blocksViews;
    }

    /**
     * @return array
     * @throws \ProcessWire\WireException
     */
    private function getNavigation()
    {
        $navigationConf = $this->pages()->get('template=navigation');
        $home = $this->pages()->get('template=home');
        $navigation = [
            'icon' => $navigationConf->image->size(300,100),
            'current' => $this->page()->title,
            'home' => $home,
            'items' => $home->children()
        ];

        return $navigation;
    }
}