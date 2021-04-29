<?php

namespace App\Services;

use ProcessWire\Page;
use ProcessWire\RepeaterPageArray;

class BlockServices
{
    const APP_BLOCKS = [
        'block_text_slider',
        'block_cube_portfolio'
    ];

    /** @var array */
    private $selectedBlocks = [];

    /**
     * @param Page $page
     * @return array
     */
    public function getBlocks(Page $page)
    {
        foreach (self::APP_BLOCKS as $blockname) {
            $block = $page->get($blockname);
            if ($block !== null) {
                $this->selectedBlocks[$blockname] = $block;
            }
        }

        return $this->selectedBlocks;
    }
}