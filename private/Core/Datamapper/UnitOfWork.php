<?php

namespace GameOfThronesMonopoly\Core\Datamapper;

use Exception;

/**
 * Class UnitOfWork
 * @package Core\Datamapper
 */
class UnitOfWork
{
    private array $query = [];
    /**
     * @var EntityManager
     */
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $entity
     * @return bool
     */
    public function persist($entity): bool
    {
        $this->query['update'][] = $entity;

        return true;
    }

    /**
     * @param $entity
     * @return bool
     */
    public function remove($entity): bool
    {
        $this->query['remove'][] = $entity;
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function flush(): bool
    {
        $db = $this->em->getConn()->getPDO();
        try {
            $db->beginTransaction();
            foreach ($this->query as $key => $value) {
                var_dump($key);
                if ($key == 'update') {
                    $this->_updateOrInsert($value);
                    $this->query['update'] = [];
                } elseif ($key == 'remove') {
                    $this->_remove($value);
                    $this->query['remove'] = [];
                } else {
                    $db->rollBack();
                    return false;
                }
            }

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        return true;
    }

    /**
     * @param $entityArray
     * @return void
     */
    private function _updateOrInsert($entityArray): void
    {
        foreach ($entityArray as $entity) {
            if (method_exists($entity, "getPrimaryKey")) {
                var_dump($entity);
                if ($entity->getPrimaryKey() == null) {
                    $this->_insertInto($entity);
                } else {
                    $this->_update($entity);
                }
            } else {
                var_dump("HALLO");
                return;
            }
        }
    }

    /**
     * @param BaseEntity $entity
     */
    public function _insertInto(BaseEntity $entity): void
    {
        $db = $this->em->getConn()->getPDO();
        $class = get_class($entity);
        $classPath = explode("\\", $class);
        $table = end($classPath);

        $insert = $this->_sqlBuilderInsert($table, $entity);
        $stmt = $db->prepare($insert['sql']);
        $stmt->execute($insert['params']);
        $entity->setPrimaryKey($db->lastInsertId());
    }

    /**
     * @param $table
     * @param $entity
     * @return array
     */
    private function _sqlBuilderInsert($table, $entity): array
    {
        $keyArray = $entity->getKeys();

        $sql = "INSERT INTO " . $table . " (";

        foreach ($keyArray as $key) {
            $sql .= "`" . $key . "`";
            if (end($keyArray) != $key) {
                $sql .= ",";
            }
        }

        $sql .= ") VALUES (";
        foreach ($keyArray as $key) {
            $sql .= ":" . $key;
            if (end($keyArray) != $key) {
                $sql .= ",";
            }
            $value = call_user_func([$entity, 'get' . ucfirst($key)]);
            if (is_string($value)) {
                $value = str_replace("'", "\\'", $value);
            }
            $params[':' . $key] = $value;
        }

        $sql .= ")";

        return ['sql' => $sql, 'params' => $params];
    }

    /**
     * @param $entity
     */
    private function _update($entity): void
    {
        $db = $this->em->getConn()->getPDO();
        $class = get_class($entity);
        $classPath = explode("\\", $class);

        $table = end($classPath);

        $update = $this->_sqlBuilderUpdate($table, $entity);

        $stmt = $db->prepare($update['sql']);

        $stmt->execute($update['params']);
    }

    /**
     * @param            $table
     * @param BaseEntity $entity
     * @return array
     */
    private function _sqlBuilderUpdate($table, BaseEntity $entity): array
    {
        $keyArray = $entity->getKeys();

        $sql = "UPDATE " . $table . " SET ";
        $params = [];

        foreach ($keyArray as $key) {
            $getFunc = "get" . ucfirst($key) . "";
            $sql .= "" . $key . " = :" . $key . "";
            $value = $entity->$getFunc();
            if (is_string($value)) {
                str_replace("'", "\\'", addslashes($value));
            }
            $params[':' . $key] = $value;
            if (end($keyArray) != $key) {
                $sql .= ", ";
            }
        }

        $where = $this->createWHEREFromPrimary($entity);

        $params += $where['params'];
        $sql .= $where['sql'];

        return ['sql' => $sql, 'params' => $params];
    }

    private function createWHEREFromPrimary(BaseEntity $entity): array
    {
        $sql = ' WHERE';
        $params = [];
        $primaryKeys = [$entity->readPrimaryKey() => $entity->getPrimaryKey()];

        $end = key($primaryKeys);
        foreach ($primaryKeys as $keyName => $value) {
            $sql .= ' ' . $keyName . ' = :' . $keyName;
            if ($end != $keyName) {
                $sql .= ' AND';
            }
            $params[':' . $keyName] = $value;
        }

        return ['sql' => $sql, 'params' => $params];
    }

    /**
     * @param $entityArray
     * @return void
     */
    private function _remove($entityArray): void
    {
        foreach ($entityArray as $entity) {
            $this->_deleteEntity($entity);
        }
    }

    /**
     * @param $entity
     * @return bool
     */
    private function _deleteEntity($entity): bool
    {
        $db = $this->em->getConn()->getPDO();
        if ($entity == null) {
            return false;
        }
        $class = get_class($entity);
        $classPath = explode("\\", $class);
        $table = end($classPath);
        $delete = $this->_sqlBuilderDelete($table, $entity);

        if ($entity->getPrimaryKey() != null) {
            $stmt = $db->prepare($delete['sql']);

            $stmt->execute($delete['params']);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $table
     * @param $entity
     * @return array
     */
    private function _sqlBuilderDelete($table, $entity): array
    {
        $sql = "DELETE FROM " . $table;

        $where = $this->createWHEREFromPrimary($entity);
        $params = $where['params'];
        $sql .= $where['sql'];

        return ['sql' => $sql, 'params' => $params];
    }
}