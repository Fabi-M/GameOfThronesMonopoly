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
     * @author Fabian Müller
     */
    public function EndTurnAction(): void
    {
        try{
            $gameService = new GameService();
            $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
            $response = $game->endTurn($this->em);
            $this->em->flush();
        }catch(\Throwable $e){
            $response = [
                "success" => false,
                "error" => $e->getMessage()
            ];
        }

        echo json_encode($response);
    }

    /**
     * Rolls the Dice for Moving
     * @url    /Roll/Move
     * @return void
     * @throws Exception
     * @author Christian Teubner, Selina Stöcklein
     */
    public function RollForMoveAction(): void
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
        $gameService->checkIfAllowedToEndTurn($rolled);
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
    public function RollForEscapeAction(): void
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $dice = new Dice();
        $rolled = $dice->roll();
        echo json_encode(
            [
                'dice' => $rolled,
                'escaped' => $rolled[0] == $rolled[1],
                'activePlayerId' => $game->getGameEntity()->getActivePlayerId()
            ]
        );
    }

    /**
     * Start a new game
     * @url    /StartGame
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function StartNewGame(): void
    {
        try{
            $gameService = new GameService();
            $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
            $response = [
                "success" => true,
                "id" => $this->sessionId,
            ];
            $this->em->flush();
        }catch(\Throwable $e){
            $response = [
                "success" => "false",
                "error" => $e->getMessage()
            ];
        }

        echo json_encode($response);
    }

    /**
     * Show the Homepage
     * @url    /Homepage
     * @return void
     * @throws Exception
     * @author Christian Teubner
     */
    public function ShowHomepageAction(): void
    {
        echo $this->twig->render(
            "Game/views/StartPage.html.twig",
            [
            ]
        );
    }
}