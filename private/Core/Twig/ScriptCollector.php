<?php


namespace Core\Twig;


class ScriptCollector
{
    private $scriptPaths;

    public function __construct()
    {
        $this->scriptPaths=array();
    }

    /**
     * @return array
     */
    public function getScriptPaths()
    {
        return $this->scriptPaths;
    }

    public function addTop(string $path)
    {
        array_unshift($this->scriptPaths, $path);
    }

    public function addBottom(string $path)
    {
        array_push($this->scriptPaths, $path);
    }

}