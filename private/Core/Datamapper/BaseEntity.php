<?php

namespace GameOfThronesMonopoly\Core\Datamapper;

/**
 * Class BaseEntity

 * @primaryKey
 * @Repository
 */
class BaseEntity
{

    private $attributes;

    private $reflectionClass;
    /**
     * BaseEntity constructor.
     */
    public function __construct(){
        $this->reflectionClass           = new \ReflectionClass(get_class($this));
    }

    public function getAttributes(){
        if($this->attributes == null){
            $properties = $this->reflectionClass->getProperties();
            foreach ($properties as $property) {
                $this->attributes[] = $property->name;
            }

        }

        return $this->attributes;
    }
    /**
     * @param $input
     */
    public function setPrimaryKey($input): void
    {
        call_user_func(array($this, 'set' . ucfirst($this->readPrimaryKey())), $input);
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey(): mixed
    {
        var_dump('get' . ucfirst($this->readPrimaryKey()));
        return call_user_func(array($this, 'get' . ucfirst($this->readPrimaryKey())));
    }

    /**
     * Liest den PrimaryKey aus der Annotation
     */
    public function readPrimaryKey(): string
    {
        $reflect = new \ReflectionClass(new $this());

        return trim(explode("primaryKey", substr(explode("@", $reflect->getDocComment())[1], 0, -2))[1]);
    }

    /**
     * @return mixed
     */
    public function getRepositoryName(): mixed
    {
        return $this->readRepository();
    }

    /**
     * Liest das Repository aus der Annotation
     */
    public function readRepository(): string
    {
        $reflect = new \ReflectionClass(new $this());
        $test2 = substr($reflect->getDocComment(), 3, -2);
        return trim(explode("@Repository", $test2)[1]);
    }


    /**
     * @return array
     */
    public function __toArray(): array
    {
        $result = array();
        foreach ($this->getAttributes() as $attributeName)
        {
            $result[$attributeName] =  call_user_func(array($this, 'get' . ucfirst($attributeName)));
        }

        return $result;
    }

    public function fillClass($classArray): void
    {
        foreach($classArray as $attribute => $value){
            if(!method_exists($this, 'set' . ucfirst($attribute))){
                continue;
            }

            call_user_func(array($this, 'set' . ucfirst($attribute)), $value);
        }
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->getAttributes();
    }
}
