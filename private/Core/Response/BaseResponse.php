<?php

namespace GameOfThronesMonopoly\Core\Response;

use GameOfThronesMonopoly\Core\Exceptions\ResponseException;
use GameOfThronesMonopoly\Core\Strings\ResponseExceptionString;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

/**
 * Class BaseResponse
 * Parent of all responses, holds some basic properties that have to exist
 * It can build a json of each child response
 */
class BaseResponse
{

    /** @var string */
    protected $errorMessage;

    /** @var string */
    protected $backendErrorMessage;

    /** @var bool */
    protected $success;


    // <editor-fold defaultstate="collapsed" desc="Setter">


    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }


    /**
     * @param string $backendErrorMessage
     */
    public function setBackendErrorMessage(string $backendErrorMessage): void
    {
        $this->backendErrorMessage = $backendErrorMessage;
    }

    // </editor-fold>


    // <editor-fold defaultstate="collapsed" desc="Get Formatted Message">


    /**
     * Get a json of the requested response
     * @return string
     * @throws ResponseException
     */
    public function getFormattedMsg(): string
    {
        $messageInstance = new stdClass();
        $reflection = new ReflectionClass($this);

        // get all properties that are public, protected or private
        $reflectionProperties = $reflection->getProperties(
            ReflectionProperty::IS_PUBLIC
            | ReflectionProperty::IS_PROTECTED
            | ReflectionProperty::IS_PRIVATE);

        // get all static properties
        $staticProperties = $reflection->getProperties(ReflectionProperty::IS_STATIC);

        // remove static properties; I don't want static properties to be in our json!
        $reflectionProperties = array_diff($reflectionProperties, $staticProperties);

        // build a stdClass containing all required properties with their values
        /** @var ReflectionProperty $reflectionProperty */
        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName = $reflectionProperty->name;
            $propertyValue = $this->$propertyName;

            // check if value is valid
            if (is_null($propertyValue)) {
                throw new ResponseException(sprintf(ResponseExceptionString::INVALID_VALUE, $propertyName));
            }

            $messageInstance->$propertyName = $propertyValue;
        }
        $messageInstance->className = $reflection->getShortName();// set className

        // encode stdClass to JSON
        return json_encode($messageInstance);
    }

    // </editor-fold>


}