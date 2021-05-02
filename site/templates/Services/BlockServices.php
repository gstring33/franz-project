<?php

namespace App\Services;

use ProcessWire\Config;
use ProcessWire\Page;

class BlockServices
{
    /** @var string  */
    private $blocksDirectory;

    /**
     * BlockServices constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->blocksDirectory = $config->twigTemplates . '/blocks/';
    }

    /**
     * @param Page $page
     * @return array
     */
    public function getBlocks(Page $page)
    {
        $selectedBlocks = [];
        foreach ($page->getFields() as $field) {
            $fieldName = $field->name;
            if (file_exists($this->blocksDirectory . $fieldName . '.html.twig')) {
                $selectedBlocks[$fieldName] = $page->get($fieldName);
            }
        }

        return $selectedBlocks;
    }
}