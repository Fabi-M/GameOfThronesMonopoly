<?php


namespace GameOfThronesMonopoly\Core\Datamapper;


use Exception;
use GameOfThronesMonopoly\Core\Datamapper\Repository\DefaultRepositoryFactory;
use GameOfThronesMonopoly\Core\Datamapper\Repository\MainRepositoryClass;
use ReflectionException;

/**
 * Class EntityManager
 * @package Core\Datamapper
 */
class EntityManager
{
    private Connection $conn;

    private UnitOfWork $unitOfWork;

    private bool $allowChanges = true;
    private DefaultRepositoryFactory $repositoryFactory;

    /**
     * EntityManager constructor.
     */
    public function __construct($db)
    {
        $this->conn = new Connection($db);
        $this->unitOfWork = new UnitOfWork($this);
        $this->repositoryFactory=new DefaultRepositoryFactory();
    }

    /**
     * @return Connection |null
     */
    public function getConn(): ?Connection
    {
        return $this->conn;
    }


    /**
     * @param $entityName
     * @return MainRepositoryClass
     * @throws ReflectionException
     */
    public function getRepository($entityName)
    {
        if (str_contains($entityName, ":")) {
            $bundleAndEntity = explode(":", $entityName);
            //     $entity = "" . $bundleAndEntity[0] . "\\" . $bundleAndEntity[1] . "";
            $entity = "" . $bundleAndEntity[0] . "\\" . $bundleAndEntity[1] . "\\" . $bundleAndEntity[2] . "";

            //Model
            $reflectionEntity = new \ReflectionClass(new $entity());
            return $this->repositoryFactory->getRepository($this, $reflectionEntity);
        }

        return null;
    }

    /**
     * @param $entity
     * @return bool|void
     */
    public function persist($entity)
    {
        if (!$this->allowChanges) {
            return;
        }
        return $this->unitOfWork->persist($entity);
    }

    /**
     * @param $entity
     * @return bool|void
     */
    public function remove($entity)
    {
        if (!$this->allowChanges) {
            return;
        }
        return $this->unitOfWork->remove($entity);
    }

    /**
     * @throws Exception
     */
    public function flush()
    {
        if (!$this->allowChanges) {
            return;
        }

        return $this->unitOfWork->flush();
    }

    /**
     * @param bool $allowChanges
     */
    public function setAllowChanges(bool $allowChanges): void
    {
        $this->allowChanges = $allowChanges;
    }


    public function setFuturePrimaryKey($entity): void
    {
        $this->unitOfWork->_insertInto($entity);
    }
}