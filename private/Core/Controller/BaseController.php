<?php

namespace GameOfThronesMonopoly\Core\Controller;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Core\Twig\ScriptCollector;
use GameOfThronesMonopoly\Core\Twig\StyleSheetCollector;
use PDO;
use GameOfThronesMonopoly\Core\DataBase\DataBaseConnection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


/**
 * Class BaseController
 * Parent of all controllers, builds some basic objects and checks the users permission
 */
class BaseController
{
    /** @var EntityManager */
    protected $em;

    protected StyleSheetCollector $styleSheetCollector;

    /** @var PDO */
    protected $pdo;

    protected $sessionId;

    protected const IMGPATH = 'http://hosting175021.ae88e.netcup.net/img/';

    /**
     * @var Environment
     */
    protected $twig;

    /** @var string[] */
    private $baseJSFiles = array(
        '/js/Core/Gamemanager.js'
    );

    /** @var string[] */
    private $baseCSSFiles = array(
    );

    /**
     * Constructor
     * Set some properties and errorHandlers
     */
    public function __construct()
    {
        if(!isset($_SESSION)){
            session_start();
            $this->sessionId = session_id();
        }
        $pdo = DataBaseConnection::getInstance()->getConnection();
        $this->em = new EntityManager($pdo);
        $this->pdo = $pdo;
        $loader = new FilesystemLoader('../private/');
        $this->twig = new Environment($loader, ['cache' => false]);

        $this->addTwig();

        register_shutdown_function(array($this, "fatalErrorHandler"));
    }

    private function addTwig()
    {
        $loader = new FilesystemLoader('../private/');
        $this->twig = new Environment($loader, ['cache' => false]);
        $this->twig->addGlobal('BASEPATH', "http://localhost/GameOfThronesMonopoly"); // add base path to twigs global variables, like 'https://azubis.upjers.com/awesomestoragetool'

        $this->addScriptCollector();
        $this->addStyleSheetCollector();
    }

    /**
     * add all needed js file paths
     */
    private function addScriptCollector()
    {
        // add basic js scripts
        $scriptCollector = new ScriptCollector();
        $scripts = $this->baseJSFiles;
        foreach ($scripts as $scriptPath) {
            $scriptCollector->addBottom($scriptPath);
        }
        $this->scriptCollector = $scriptCollector;
        $this->twig->addGlobal('SCRIPTCOLLECTOR', $this->scriptCollector);
    }

    /**
     * add all needed css file paths
     */
    private function addStyleSheetCollector()
    {
        // add basic css scripts
        $styleSheetCollector = new StyleSheetCollector();
        $scripts = $this->baseCSSFiles;
        foreach ($scripts as $scriptPath) {
            $styleSheetCollector->addBottom($scriptPath);
        }
        $this->styleSheetCollector = $styleSheetCollector;
        $this->twig->addGlobal('STYLESHEETCOLLECTOR', $this->styleSheetCollector);
        
    }


    /**
     * Handle fatal errors
     * @return void
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
     */
    public function errorHandler(int $errorNumber, string $errorString, string $errorFile, int $errorLine)
    {
        ob_start();
        debug_print_backtrace();
        $trace = ob_get_clean();

        $msg = "Error: [$errorNumber] $errorString --- Error on line $errorLine in $errorFile " . 'Trace: ' . $trace;

        error_log("Errorhandler " . $msg);
    }
}