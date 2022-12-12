<?php
namespace GameOfThrones\Core\Datamapper\Repository;

class getRepositories
{
    private array $repositoryList = [];

    protected static ?getRepositories $_instance = null;

    /**
     * @return getRepositories|null
     */
    public static function getInstance(): ?getRepositories
    {
        if (null === self::$_instance)
        {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * @param $repoNamespace
     * @param $repoClass
     */
    public function addRepository($repoNamespace, $repoClass): void
    {
        $this->repositoryList[$repoNamespace] = $repoClass;
    }

    /**
     * @param $repo
     * @return bool|mixed
     */
    public function getRepositories($repo): mixed
    {
        if(array_key_exists($repo, $this->repositoryList))
        {
            return $this->repositoryList[$repo];
        }
        else
        {
            return false;
        }
    }

    protected function __clone(){}

    protected function __construct(){}
}



