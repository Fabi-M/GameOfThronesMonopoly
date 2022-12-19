<?php

namespace GameOfThronesMonopoly\Game\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Factories\GameFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Model\Dice;
use GameOfThronesMonopoly\Game\Model\GameManager;
use GameOfThronesMonopoly\Game\Model\Player;
use GameOfThronesMonopoly\Game\Service\GameService;

class GameManagerController extends BaseController
{
    public function TestAction()
    {
        var_dump($this->sessionId);
        echo $this->twig->render(
            "Core/Views/Base.html.twig",
            [
            ]
        );
    }

    /**
     * Ends the current turn
     * @url    /EndTurn
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @author Fabian Müller
     */
    public function EndTurnAction()
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $game->endTurn($this->em);
        $this->em->flush();

        //TODO 16.12.2022 Selina: Hier SpielerId zurückgeben, damit im PopUp
        // gezeigt werden kann wer als nächstes dran ist
        // echo next player
        echo $this->twig->render(
            "Core/Views/Base.html.twig",
            [
            ]
        );
    }

    /**
     * Rolls the Dice for Moving
     * @url    /Roll/Move
     * @return void
     * @author Christian Teubner, Selina Stöcklein
     */
    public function RollForMoveAction()
    {
        // get the current game
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        // build a dice and roll
        $dice = new Dice();
        $rolled = $dice->roll();
        // get the active player and let it move
        /** @var Player $activePlayer */
        $activePlayer = PlayerFactory::getActivePlayer($this->em, $game);
        $playFieldId = $activePlayer->move($this->em, $rolled);
        // save
        $this->em->flush();
        echo json_encode([
                             'dice' => $rolled,
                             'playFieldId' => 0,
                             'activePlayerId' => $game->getGameEntity()->getActivePlayerId()
                         ]);
    }

    /**
     * Rolls the Dice for Escaping
     * @url    /Roll/Escape
     * @return void
     * @author Christian Teubner, Selina Stöcklein
     */
    public function RollForEscapeAction()
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $dice = new Dice();
        $rolled = $dice->roll();
        echo json_encode([
                             'dice' => $rolled,
                             'escaped' => $rolled[0] == $rolled[1],
                             'activePlayerId' => $game->getGameEntity()->getActivePlayerId()
                         ]);
    }

    /**
     * Start a new game
     * @url    /StartGame
     * @return void
     * @throws \Exception
     * @author Fabian Müller
     */
    public function StartNewGame()
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $this->em->flush();
        //TODO 16.12.2022 Selina: Spielstand returnen, damit HTML CSS was anzeigen kann
    }
}