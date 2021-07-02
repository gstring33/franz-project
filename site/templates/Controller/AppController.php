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
        $teaser = $this->render('@partials/home_teaser.html.twig', [
            'teaserTitle' => $this->page()->get('title2'),
            'teaserTitle2' => $this->page()->get('title3')
        ]);

        echo $this->render('@content/home.html.twig', [
            'teaser' => $teaser,
            'blocks' => $blocksViews,
        ]);
    }

    public function workshopSingle()
    {
        echo $this->render('@content/workshop-single.html.twig', [
            'workshop' => $this->page()
        ]);
    }

    public function workshops()
    {
        echo $this->render('@content/workshops.html.twig', []);
    }

    public function aboutMe()
    {
        $blocksViews = $this->getBlocksViews();

        echo $this->render('@content/about-me.html.twig', [
            'blocks' => $blocksViews
        ]);
    }

    public function contact()
    {
        echo $this->render('@content/contact.html.twig', [
            'content' => $this->page()
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
}