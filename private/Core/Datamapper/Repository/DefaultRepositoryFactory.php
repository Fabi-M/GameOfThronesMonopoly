<?php

namespace GameOfThronesMonopoly\Core\Datamapper\Repository;

use ReflectionClass;
use ReflectionException;

/**
 * Class DefaultRepositoryFactory
 * @package Core\Datamapper\Repository
 */
class DefaultRepositoryFactory implements RepositoryFactory
{
    private $repositoryList;

    /**
     * @param                  $em
     * @param ReflectionClass $entity
     * @return mixed
     * @throws ReflectionException
     */
    public function getRepository($em, ReflectionClass $entity):mixed
    {
        $this->repositoryList = getRepositories::getInstance();

        $ent = $entity->newInstance();
        $repo = $ent->getRepositoryName();
        if ($this->repositoryList->getRepositories($repo)) {
            /**
             * @return $repo
             */
            return $this->repositoryList->getRepositories($repo);
        } else {
            return $this->createRepository($em, $repo, $entity);
        }
    }

    /**
     * @param $em
     * @param $repoNamespace
     * @param $entity
     * @return mixed
     */
    private function createRepository($em, $repoNamespace, $entity): mixed
    {
        $repoNamespace = false;
        if (method_exists($entity, 'getClass')) {
            $entity = $entity->getClass();
        }
        if (class_exists($repoNamespace)) {
            $newRepo = new $repoNamespace($entity, $em);
        } else {
            $repoNamespace = $entity->getShortName();
            $newRepo = new MainRepositoryClass($entity, $em);
        }

        getRepositories::getInstance()->addRepository($repoNamespace, $newRepo);

        return $newRepo;
    }


}
