<?php

namespace GameOfThronesMonopoly\Game\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Factories\GameFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
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
        var_dump($this->sessionId);
        var_dump($this->em);
        echo "HALLOOOO";
        $game = GameFactory::filterOne($this->em, array(
            'WHERE' => array('session_id', 'equal', $this->sessionId)
        ));
        var_dump($game);
        $game->EndTurn($this->em);
        $this->em->flush();

        echo $this->twig->render("Core/Views/Base.html.twig",
            [
            ]);
    }
}