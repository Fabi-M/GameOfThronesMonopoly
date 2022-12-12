<?php

namespace GameOfThronesMonopoly\Core\Response;


use GameOfThronesMonopoly\Core\Exceptions\ResponseException;

/**
 * Class BackendResponse
 * This response will always be sent to our frontend, after each request!
 * It contains all responses build during the executed Action.
 * It could contain: ErrorResponse, SignupResponse, LoginResponse, etc. ...
 */
class BackendResponse extends BaseResponse
{

    /** @var string[] */
    protected $responses = [];


    /**
     * Add a new Response to the array
     * @param BaseResponse $response
     * @return void
     * @throws ResponseException
     */
    public function addResponse(BaseResponse $response)
    {
        $this->responses[] = $response->getFormattedMsg();
    }

}