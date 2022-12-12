<?php

namespace GameOfThrones\Core\Datamapper\Repository;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;
use PDO;
use ReflectionClass;
use ReflectionException;

/**
 * Class MainRepositoryClass
 * @package Core\Datamapper\Repository
 */
class MainRepositoryClass
{

    private $entityName;

    private ReflectionClass $reflectionEntity;

    private array $operators =
        array(
            'equal'              => '=',
            'notEqual'           => '<>',
            'greaterThan'        => '>',
            'greaterThanOrEqual' => '>=',
            'lessThan'           => '<',
            'lessThanOrEqual'    => '<=',
            'like'               => ' LIKE ',
            'notlike'            => ' NOT LIKE ',
            'isNull'             => ' IS NULL ',
            'isNotNull'          => ' IS NOT NULL '
        );

    private $em;

    /**
     * @var PDO
     */
    protected PDO $db;

    /**
     * MainRepositoryClass constructor.
     * @param $em
     * @param ReflectionClass $entity
     */
    public function __construct(ReflectionClass $entity, $em)
    {
        $this->em               = $em;
        $this->db               = $this->em->getConn()->getPDO();
        $this->reflectionEntity = $entity;
    }

    /**
     * @param $id
     * @return array|mixed|string
     */
    public function find($id): mixed
    {
        $sql = "SELECT * FROM " . $this->reflectionEntity->getShortName() . " WHERE ID = ':id'";

        return $this->Executer($this->db, $sql, array(':id' => $id));
    }

    /**
     * @param $criteria
     * @return BaseEntity[]|BaseEntity|null
     * @throws Exception
     */
    public function findOneBy($criteria): BaseEntity|array|null
    {
        $criteria['LIMIT']['LIMIT'] = 1;
        return $this->findBy($criteria);
    }

    /**
     * @param $criteria
     * @param $connector
     * @return array
     * @throws Exception
     */
    public function buildSQL($criteria, $connector): array
    {
        $sql    = "SELECT * FROM " . $this->reflectionEntity->getShortName();

        $params = array();
        if (isset($criteria) && $criteria != array()) {
            if (isset($criteria['WHERE']) && $criteria['WHERE'] != array()) {
                $sql     .= " WHERE ";
                $counter = 0;
                foreach ($criteria["WHERE"] as $key => $whereClauses) {
                    if (count($whereClauses) <= 2 || count($whereClauses) >= 5) {
                        throw new \Exception('Invalid Where Clause ' . json_encode($whereClauses));
                    }
                    if ($whereClauses[1] == 'isNull' || $whereClauses[1] == 'isNotNull') {
                        $sql .= $whereClauses[0] . " " . $this->getOperator($whereClauses[1]) . " " . addslashes($whereClauses[2]) . "";
                    } else {
                        $sql                            .= $whereClauses[0] . " " . $this->getOperator($whereClauses[1]) . " :" . $key . addslashes($whereClauses[0]) . "";
                        $params[':'.$key . $whereClauses[0]] = $whereClauses[2];
                    }

                    if ($counter < sizeof($criteria["WHERE"]) - 1) {
                        if(isset($whereClauses[3])){
                            $connector = $whereClauses[3];
                        }
                        $sql .= " " . $connector . " ";
                        $counter++;
                    }
                }
            }
            if (isset($criteria['IN']) && $criteria['IN'] != array()) {
                if (!isset($criteria['WHERE']) || $criteria['WHERE'] == array()) {
                    $sql .= ' WHERE ';
                }else{
                    $sql .= ' AND ';
                }
                foreach ($criteria["IN"] as $key => $values) {
                    if ($values == array()) {
                        continue;
                    }
                    $sql .= $key . ' IN("' . implode('","', $values) . '")';
                }
            }
            if (isset($criteria['GROUP']) && $criteria['GROUP'] != array()) {
                $sql     .= " GROUP BY ";
                $counter = 0;
                foreach ($criteria["GROUP"] as $key => $value) {
                    $sql .= $value . "";
                    if ($counter < sizeof($criteria["GROUP"]) - 1) {
                        $sql .= ", ";
                        $counter++;
                    }
                }
            }
            if (isset($criteria['ORDER']) && $criteria['ORDER'] != array()) {
                $sql     .= " ORDER BY ";
                $counter = 0;
                foreach ($criteria["ORDER"] as $key => $value) {
                    $sql .= $key . " " . $value . "";
                    if ($counter < sizeof($criteria["ORDER"]) - 1) {
                        $sql .= ", ";
                        $counter++;
                    }
                }
            }

            if (isset($criteria['LIMIT']) && $criteria['LIMIT'] != array()) {
                if (isset($criteria['LIMIT']['OFFSET'])) {
                    $sql .= " LIMIT " . $criteria['LIMIT']['OFFSET'] . ' , ' . $criteria['LIMIT']['LIMIT'];
                } else if ($criteria['LIMIT']['LIMIT']) {
                    $sql .= " LIMIT " . $criteria['LIMIT']['LIMIT'];
                }
            }
        }


        return array('sql' => $sql, 'params' => $params);
    }

    //$criteria Aufbau
    //[
    // "WHERE" => array([0] => array('username', 'notEqual', 'test')),
    // "ORDER" => array(['time' => 'DESC'),
    // "GROUP" => array(),
    // "IN"    => array(),
    // "LIMIT  => array('LIMIT' => 0, 'OFFSET' => 1)
    //]
    /**
     * @param $criteria
     * @param string $connector
     * @return BaseEntity[]
     * @throws Exception
     */
    public function findBy($criteria, string $connector = 'AND')
    {
        if (!in_array($connector, array('OR', 'AND'))) {
            throw new Exception();
        }
        if (!$this->validateCriteria($criteria) && $criteria != array()) {
            throw new \Exception('Format Fehler: Order- oder Group-Element ist kein Spaltenname!');
        }

        $sql = $this->buildSQL($criteria, $connector);
        if (isset($criteria['LIMIT']) && $criteria['LIMIT']['LIMIT'] == 1) {
            return $this->Executer($this->db, $sql['sql'], $sql['params'], true);
        }

        return $this->Executer($this->db, $sql['sql'], $sql['params']);
    }

    public function count($criteria, $connector = 'AND'){
        if (isset($criteria['GROUP']) && $criteria['GROUP'] != array()) {
            throw new \Exception('Counts can\'t be grouped');
        }
        $sql = $this->buildSQL($criteria, $connector);

        $sql['sql'] = str_replace('SELECT * FROM', 'SELECT COUNT(*) as counter FROM', $sql['sql']);

        $stmt = $this->db->prepare($sql['sql']);

        $stmt->execute($sql['params']);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);

        return intval($count['counter']);
    }

    /**
     * @return BaseEntity[]|null
     * @throws Exception
     */
    public function findAll()
    {
        return $this->findBy(array());
    }

    /**
     * @param $db
     * @param $sql
     * @param $params
     * @param $findOneBy
     * @return array|BaseEntity|mixed|null
     * @throws ReflectionException
     */
    private function Executer($db, $sql, $params, $findOneBy = false)
    {
        $data = array();

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            /** @var BaseEntity $entity */
            $entity = $this->reflectionEntity->newInstance();
            foreach ($row as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($entity, $method)) {
                    $entity->$method($value);
                }
            }

            $data[] = $entity;
        }

        $db = null;

        if ($data != null) {
            if ($findOneBy) {
                return $data[0];
            }
            return $data;
        }

        return null;
    }

    public function getOperator($operator)
    {
        if (!isset($this->operators[$operator])) {
            throw new \Exception('Invalid Database Operation: ' . $operator);
        }

        return $this->operators[$operator];
    }

    /**
     * @param $criteria
     * @return bool
     */
    private function validateCriteria($criteria): bool
    {
        $validGroup = false;
        $validOrder = false;

        foreach ($criteria as $key => $value) {

            if ($key == 'GROUP') {
                $validGroup = $this->validateGroup($value);
            }
            if ($key == 'ORDER') {
                $validOrder = $this->validateOrder($value);
            }
        }

        if (!isset($criteria["GROUP"])) {
            $validGroup = true;
        }
        if (!isset($criteria["ORDER"])) {
            $validOrder = true;
        }

        if ($validGroup && $validOrder) {
            return true;
        }

        return false;

    }

    /**
     * @param $groupValues
     * @return bool
     */
    private function validateGroup($groupValues): bool
    {
        $valid = true;

        foreach ($groupValues as $value) {
            if (!$this->reflectionEntity->hasProperty($value)) {
                $valid = false;
                break;
            }
        }

        return $valid;

    }
    /**
     * @param $property
     * @return bool
     */
    protected function validateProperty($property): bool
    {
        if (!$this->reflectionEntity->hasProperty($property) && !$this->reflectionEntity->hasProperty(strtolower($property))) {
            return false;
        }

        return true;
    }

    /**
     * @param $orderValues
     * @return bool
     */
    protected function validateOrder($orderValues): bool
    {
        $valid = true;
        foreach ($orderValues as $key => $value) {
            if (!$this->reflectionEntity->hasProperty($key)) {
                $valid = false;
                break;
            }
        }

        return $valid;
    }


    /**
     * @param $orderValues
     * @return string
     * @throws Exception
     */
    protected function createOrderBy($orderValues): string
    {
        $orderBy = '';
        foreach ($orderValues as $key => $value) {
            if (!in_array($value, array('ASC', 'DESC'))) {
                throw new \Exception('Invalid Order By ' . $value);
            }
            if ($orderBy == '') {
                $orderBy = 'ORDER BY ' . $key . ' ' . $value;
            } else {
                $orderBy .= ',' . $key . ' ' . $value;
            }
        }

        return $orderBy;
    }

    protected function createIN($in, $where = false): array|string
    {
        $sql = ' AND ';
        foreach ($in as $key => $values) {
            if ($values == array()) {
                continue;
            }
            $sql .= $key . '  IN(' . implode(',', $values) . ') ';
        }

        if ($where == true && $in !== array()) {
            $sql = substr_replace($sql, 'WHERE', 0, 3);
        }

        return $sql;
    }

    /**
     * @param $groupValues
     * @return string
     */
    protected function createGroupBy($groupValues): string
    {
        $groupBy = '';
        foreach ($groupValues as $key => $value) {
            if ($groupBy == '') {
                $groupBy = 'GROUP BY ' . $value;
            } else {
                $groupBy .= ',' . $value;
            }
        }

        return $groupBy;
    }

    /**
     * @param $limit
     * @param $offset
     * @return bool|string
     * @throws Exception
     */
    protected function createLimit($limit, $offset): bool|string
    {
        if (!is_numeric($limit) || !is_numeric($offset)) {
            throw new Exception('Limit or Offset not Numeric');
        }

        return 'LIMIT ' . $offset . ',' . $limit;
    }


    protected function createFilter($filter, $where = true, $prefix = ''): array
    {
        $sql    = '';
        $params = array();

        foreach ($filter as $key => $value) {
            $keyReplace = $key;
            if (str_contains($key, '.')) {
                $keyReplace = explode('.', $key)[1];
            }
            $operator = '=';
            if (str_contains($value, '%')) {
                $operator = 'LIKE';
            }
            $sql                 .= 'AND ' . $prefix . $key . ' ' . $operator . ' :' . $keyReplace . ' ';
            $params[$keyReplace] = $value;
        }

        if ($where == true && $filter !== array()) {
            $sql = substr_replace($sql, 'WHERE', 0, 3);
        }

        return array('sql' => $sql, 'params' => $params);
    }

}
