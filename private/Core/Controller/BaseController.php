<?php

namespace GameOfThronesMonopoly\Core\Controller;

use PDO;
use GameOfThronesMonopoly\Core\DataBase\DataBaseConnection;
use GameOfThronesMonopoly\Core\Exceptions\ResponseException;
use GameOfThronesMonopoly\Core\Response\BaseResponse;
use GameOfThronesMonopoly\Core\Response\ErrorResponse;
use GameOfThronesMonopoly\Core\Response\Service\ResponseService;
use GameOfThronesMonopoly\Core\Strings\ExceptionString;
use GameOfThronesMonopoly\User\Factories\UserFactory;
use GameOfThronesMonopoly\User\Model\User;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Up\Datamapper\EntityManager;


/**
 * Class BaseController
 * Parent of all controllers, builds some basic objects and checks the users permission
 */
class BaseController
{

    // <editor-fold defaultstate="collapsed" desc="Attributes">

    /** @var EntityManager */
    protected $em;

    /** @var PDO */
    protected $pdo;

    /** @var ResponseService */
    protected $responseService;

    /**
     * @var Environment
     */
    protected $twig;

    /** @var User */
    protected $activeUser;

    /** @var string[] */
    private $baseJSFiles = array(
        '/js/Gamemanager.js'
    );

    // </editor-fold>


    // <editor-fold defaultstate="collapsed" desc="Constructor">

    /**
     * Constructor
     * Set some properties and errorHandlers
     */
    public function __construct()
    {
        $pdo = DataBaseConnection::getInstance()->getConnection();
        $this->em = new EntityManager($pdo);
        $this->pdo = $pdo;
        $this->responseService = ResponseService::getInstance();
        $loader = new FilesystemLoader('../private/');
        $this->twig = new Environment($loader, ['cache' => false]);

        $request = $_POST['request'];
        if (!empty($request->UserId)) {
            $this->activeUser = UserFactory::filterOne($this->em, array(
                'WHERE' => array('user_id', 'equal', $request->UserId)
            ));
        }

        $this->addTwig();

        register_shutdown_function(array($this, "fatalErrorHandler"));
    }


    // </editor-fold>

    private function addTwig()
    {
        $loader = new FilesystemLoader('../private/');
        $this->twig = new Environment($loader, ['cache' => false]);
        $this->twig->addGlobal('BASEPATH', "http://localhost/GameOfThronesMonopoly"); // add base path to twigs global variables, like 'https://azubis.upjers.com/awesomestoragetool'

        $this->addScriptCollector();
        $this->addStyleSheetCollector();
    }

    /**
     * Check if the user has the needed permission
     * @param int $permission
     * @return bool
     */
    public function checkPermission(int $permission): bool
    {
        if ($permission == 0) return true; // permission 0 -> everyone is allowed to execute this request
        return $this->activeUser->hasPermission($permission);
    }

    /**
     * Handle fatal errors
     * @return void
     * @throws ResponseException
     */
    public function fatalErrorHandler()
    {
        $error = error_get_last();

        if ($error !== null) {
            $errorNumber = $error["type"];
            $errorFile = $error["file"];
            $errorLine = $error["line"];
            $errorString = $error["message"];

            // build an error response + add it to the current backend response
            $this->errorHandler($errorNumber, $errorString, $errorFile, $errorLine);
        }
    }

    /**
     * Add error response to backend response and print the backend response, then die
     * @param int $errorNumber
     * @param string $errorString
     * @param string $errorFile
     * @param int $errorLine
     * @return void
     * @throws ResponseException
     */
    public function errorHandler(int $errorNumber, string $errorString, string $errorFile, int $errorLine)
    {
        ob_start();
        debug_print_backtrace();
        $trace = ob_get_clean();

        $msg = "Error: [$errorNumber] $errorString --- Error on line $errorLine in $errorFile " . 'Trace: ' . $trace;

        error_log("Errorhandler " . $msg);

        $response = new ErrorResponse();
        $response->setSuccess(false);
        $response->setErrorMessage(ExceptionString::DEFAULT_USER_ERROR);
        $response->setBackendErrorMessage($msg);

        $this->responseService->addResponse($response);
        die($this->responseService->flush());
    }

    /**
     * fill some basic and needed information into the response
     * @param BaseResponse $response
     * @param bool $success
     * @param string $errorMessage
     * @param string $backendErrorMessage
     * @return void
     */
    protected function fillBaseResponse(BaseResponse $response, bool $success, string $errorMessage = '', string $backendErrorMessage = '')
    {
        $response->setBackendErrorMessage($backendErrorMessage);
        $response->setErrorMessage($errorMessage);
        $response->setSuccess($success);
    }


}