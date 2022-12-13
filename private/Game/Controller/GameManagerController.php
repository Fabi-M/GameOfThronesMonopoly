<?php

namespace GameOfThronesMonopoly\Game\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Model\GameManager;

class GameManagerController extends BaseController
{
    public function TestAction(){
        var_dump($this->sessionId);
        echo $this->twig->render("Core/Views/Base.html.twig",
            [
            ]);
    }

    public function EndTurnAction(){
        $GameManager = new GameManager();
        $GameManager->EndTurn();
    }
}