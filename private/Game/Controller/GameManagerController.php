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
     * @url /EndTurn
     * @author Fabian Müller
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function EndTurnAction(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $game->endTurn($this->em);
        $this->em->flush();

        //TODO 16.12.2022 Selina: Hier SpielerId zurückgeben, damit im PopUp
        // gezeigt werden kann wer als nächstes dran ist
        // echo next player
        echo $this->twig->render("Core/Views/Base.html.twig",
            [
            ]);
    }

    /**
     * Rolls the Dice for Moving
     * @url /Roll/Move
     * @author Christian Teubner
     * @return void
     */
    public function RollForMoveAction(){
        $dice = new Dice();
        echo json_encode($dice->roll());
    }

    /**
     * Rolls the Dice for Escaping
     * @url /Roll/Escape
     * @author Christian Teubner
     * @return void
     */
    public function RollForEscapeAction(){
        $dice = new Dice();
        json_encode($dice->roll());
    }

    /**
     * Start a new game
     * @url /StartGame
     * @author Fabian Müller
     * @return void
     * @throws \Exception
     */
    public function StartNewGame(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $this->em->flush();

        //TODO 16.12.2022 Selina: Spielstand returnen, damit HTML CSS was anzeigen kann
    }
}