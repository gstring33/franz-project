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
        $page = $this->page();
        $teaser = $this->render('@partials/home_teaser.html.twig', [
            'teaserTitle' => $page->get('title2'),
            'teaserTitle2' => $page->get('title3')
        ]);

        echo $this->render('@content/home.html.twig', [
            'title' => $page->title,
            'teaser' => $teaser,
            'blocks' => $blocksViews,
        ]);
    }

    public function workshopSingle()
    {
        $page = $this->page();
        echo $this->render('@content/workshop-single.html.twig', [
            'title' => $page->title,
            'workshop' =>$page
        ]);
    }

    public function workshops()
    {
        $page = $this->page();
        echo $this->render('@content/workshops.html.twig', [
            'title' => $page->title
        ]);
    }

    public function aboutMe()
    {
        $blocksViews = $this->getBlocksViews();

        echo $this->render('@content/about-me.html.twig', [
            'title' => $this->page()->title,
            'blocks' => $blocksViews
        ]);
    }

    public function contact()
    {
        $page = $this->page();
        echo $this->render('@content/contact.html.twig', [
            'title' => $page->title,
            'content' => $page,
            'recaptchaPublicKey' => $this->config()->recaptchaPublicKey
        ]);
    }

    public function faq()
    {
        $page = $this->page();
        echo $this->render('@content/faq.html.twig', [
            'title' => $page->title,
            'faq' => $page
        ]);
    }

    public function service()
    {
        $page = $this->page();
        echo $this->render('@content/service.html.twig', [
            'title' => $page->title,
            'service' => $page
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