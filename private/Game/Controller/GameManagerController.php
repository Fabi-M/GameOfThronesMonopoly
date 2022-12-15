<?php

namespace GameOfThronesMonopoly\Game\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Factories\GameFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Model\Dice;
use GameOfThronesMonopoly\Game\Model\GameManager;
use GameOfThronesMonopoly\Game\Service\GameService;

class GameManagerController extends BaseController
{
    public function TestAction(){
        var_dump($this->sessionId);
        echo $this->twig->render("Core/Views/Base.html.twig",
            [
            ]);
    }

    /**
     * Ends the current turn
     * @url /endTurn
     * @author Fabian Müller
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function EndTurnAction(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $game->EndTurn($this->em);
        $this->em->flush();

        echo $this->twig->render("Core/Views/Base.html.twig",
            [
            ]);
    }

    /**
     * @url /roll
     * @return void
     */
    public function RollAction(){
        $dice = new Dice();
        json_encode($dice->roll());
    }

    /**
     * Start a new game
     * @url /startGame
     * @author Fabian Müller
     * @return void
     * @throws \Exception
     */
    public function StartNewGame(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $this->em->flush();
    }
}