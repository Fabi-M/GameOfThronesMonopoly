<?php

namespace GameOfThronesMonopoly\Core\Response\Service;


use GameOfThronesMonopoly\Core\Exceptions\ResponseException;
use GameOfThronesMonopoly\Core\Response\BackendResponse;
use GameOfThronesMonopoly\Core\Response\BaseResponse;

/**
 * Class ResponseService
 * Handles our Responses
 * It got the BackendResponse and adds all responses, build during request execution
 * before end of script it flushes the backendResponse and prints it as a json, which will be returned to the frontend
 */
class ResponseService
{

    // <editor-fold defaultstate="collapsed" desc="Attributes">

    /** @var ResponseService */
    private static $instance = null;

    /** @var BackendResponse */
    private $backendResponse;

    /** @var bool  */
    private $printResponse = true;



    // </editor-fold>



    // <editor-fold defaultstate="collapsed" desc="Setter">

    /**
     * @param bool $printResponse
     */
    public function setPrintResponse(bool $printResponse): void
    {
        $this->printResponse = $printResponse;
    }

    // </editor-fold>


    // <editor-fold defaultstate="collapsed" desc="Singleton">

    /**
     * Constructor
     * Build BackendResponse
     */
    private function __construct()
    {
        $backendResponse = new BackendResponse();
        $backendResponse->setSuccess(true);
        $backendResponse->setErrorMessage('');
        $backendResponse->setBackendErrorMessage('');
        $this->backendResponse = $backendResponse;
    }

    /**
     * Get instance of ResponseService
     * @return ResponseService
     */
    public static function getInstance(): ResponseService
    {
        if (!self::$instance) {
            self::$instance = new ResponseService();
        }

        return self::$instance;
    }

    // </editor-fold>


    // <editor-fold defaultstate="collapsed" desc="Add Response">

    /**
     * Add a response to backendResponse response array
     * @param BaseResponse $response
     * @return void
     * @throws ResponseException
     */
    public function addResponse(BaseResponse $response)
    {
        $this->backendResponse->addResponse($response);
    }

    // </editor-fold>


    // <editor-fold defaultstate="collapsed" desc="Flush">

    /**
     * Return the formatted responses as a json
     * @return string
     * @throws ResponseException
     */
    public function flush(): string
    {
        if(!$this->printResponse){
            return '';
        }
        return $this->backendResponse->getFormattedMsg(); // return json
    }

    // </editor-fold>


}