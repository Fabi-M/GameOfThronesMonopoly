<?php

namespace GameOfThronesMonopoly\Core\Twig;

class StyleSheetCollector
{
    private $styleSheetPaths;

    public function __construct()
    {
        $this->styleSheetPaths = [];
    }

    /**
     * @return array
     */
    public function getStyleSheetPaths()
    {
        return $this->styleSheetPaths;
    }

    public function addTop(string $path)
    {
        array_unshift($this->styleSheetPaths, $path);
    }

    public function addBottom(string $path)
    {
        array_push($this->styleSheetPaths, $path);
    }
}