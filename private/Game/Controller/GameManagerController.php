<?php

namespace GameOfThronesMonopoly\Game\Controller;

use Exception;
use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Model\Dice;
use GameOfThronesMonopoly\Game\Service\GameService;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GameManagerController extends BaseController
{
    /**
     * Ends the current turn
     * @url    /EndTurn
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @author Fabian Müller & Christian Teubner
     */
    public function EndTurnAction()
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $nextPlayer = $game->endTurn($this->em);
        $this->em->flush();
        echo json_encode($nextPlayer);
    }

    /**
     * Rolls the Dice for Moving
     * @url    /Roll/Move
     * @return void
     * @throws Exception
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
        $activePlayer = PlayerFactory::getActivePlayer($this->em, $game);
        $playFieldId = $activePlayer->move($this->em, $rolled);
        // save
        $this->em->flush();
        echo json_encode(
            [
                'dice' => $rolled,
                'playFieldId' => $playFieldId,
                'activePlayerId' => $game->getGameEntity()->getActivePlayerId()
            ]
        );
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

    /**
     * Show the Homepage
     * @url    /Homepage
     * @return void
     * @throws \Exception
     * @author Christian Teubner
     */
    public function ShowHomepageAction()
    {
        echo $this->twig->render(
            "Game/views/StartPage.html.twig",
            [
            ]
        );
    }
}