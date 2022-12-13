<?php

namespace GameOfThronesMonopoly\Core\Datamapper\Repository;

use ReflectionClass;

/**
 * Interface RepositoryFactory
 * @package Core\Datamapper\Repository
 */
interface RepositoryFactory
{
    /**
     * @param $em
     * @param ReflectionClass $entity
     * @return mixed
     */
    public function getRepository($em, ReflectionClass $entity): mixed;
}