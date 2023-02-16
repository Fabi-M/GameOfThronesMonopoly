<?php

namespace GameOfThronesMonopoly\Game\Controller;

use Exception;
use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Game\Factories\GameFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Model\Dice;
use GameOfThronesMonopoly\Game\Model\Street;
use GameOfThronesMonopoly\Game\Repositories\StreetRepository;
use GameOfThronesMonopoly\Game\Service\GameService;
use GameOfThronesMonopoly\Game\Service\SaveGameService;

class GameManagerController extends BaseController
{
    public function InitAction(): void
    {
        try {
            // get game
            $game = GameFactory::getActiveGame($this->em, $this->sessionId);
            if ($game === null) {
                header("Location: http://localhost/GameOfThronesMonopoly/Homepage");
                die();
            }
            // get players
            $players = PlayerFactory::getPlayersOfGame($this->em, $game);
            $service = new SaveGameService();
            $saveGameConfig = $service->getPlayfieldInitConfig($game, $players, $this->em);

            // add dice
            $this->styleSheetCollector->addBottom('/css/Dice.css');
            $this->scriptCollector->addBottom('/js/Jail.js');
            // display playfield and savegame
            echo $this->twig->render(
                "Game/Views/Game.html.twig",
                [
                    'imgPath' => self::IMG_PATH,
                    'playFieldJPG' => 'Playfield.jpg',
                    'figureDir' => '/figures/',
                    'figurePNGName' => 'figur',
                    'playerFigures' => 4,
                    'saveGame' => $saveGameConfig
                ]
            );
        } catch (\Throwable $e) {
            // render error page mit link zu startpage
            echo $e->getMessage();
            //TODO 09.02.2023 Selina: render error page
        }
    }

    /**
     * Ends the current turn
     * @url    /EndTurn
     * @return void
     * @author Fabian Müller
     */
    public function EndTurnAction(): void
    {
        //TODO 09.02.2023 Selina: Daten des nächsten Spielers zurück geben
        try {
            $gameService = new GameService();
            $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
            $response = $game->endTurn($this->em);
            $this->em->flush();
        } catch (\Throwable $e) {
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
        //TODO 23.12.2022 Fabian: add try catch
        // get the current game
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        // build a dice and roll
        $dice = new Dice();
        $rolled = $dice->roll();
        // get the active player and let it move
        $activePlayer = PlayerFactory::getActivePlayer($this->em, $game);
        $playFieldId = $activePlayer->move($this->em, $rolled);
        if ($rolled[0] !== $rolled[1]) { // not for pasch
            // save that player rolled for movement
            $gameService->checkIfAllowedToEndTurn($rolled);
            $game->getGameEntity()->setRolledDice(1);
            $this->em->persist($game->getGameEntity());
        }else{
            $paschCount = $game->getGameEntity()->getPaschCount();
            $paschCount++;
            $game->getGameEntity()->setPaschCount($paschCount);
            $this->em->persist($game->getGameEntity());
            if($paschCount == 3){
                $activePlayer->goToJail($this->em);
                $playFieldId = 10;
            }
        }
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
     * @throws Exception
     * @author Christian Teubner, Selina Stöcklein
     */
    public function RollForEscapeAction(): void
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $player = PlayerFactory::getActivePlayer($this->em, $game);
        $dice = new Dice();
        $rolled = $dice->roll();
        $player->hasRolledForEscape($rolled);
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
    public function StartNewGame($playerCount): void
    {
        try {
            session_regenerate_id(true);
            $this->sessionId = session_id();
            $gameService = new GameService();
            $gameService->createGame($this->em, $this->sessionId, $playerCount);
            $response = [
                "success" => true,
                "id" => $this->sessionId,
            ];
            $this->em->flush();
        } catch (\Throwable $e) {
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
     * @author Fabian Müller
     */
    public function ShowHomepageAction(): void
    {
        $this->scriptCollector->addBottom('/js/StartPage.js');
        echo $this->twig->render(
            "Game/views/Start-Page.html.twig",
            [
                'imgPath'=>self::IMG_PATH.'menu/monopoly-title.jpg',
                'imgPathTrennlinie'=>self::IMG_PATH.'menu/trennlinie.png'
            ]
        );
    }

    /**
     * Check if the player has a game saved with current session id
     * @url    /CanLoadGame
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function CanLoadGameAction(){
        try{
            $game = GameFactory::getActiveGame($this->em, $this->sessionId);
            if ($game === null) {
                echo json_encode(false);
                return;
            }
            echo json_encode(true);
            return;
        }catch (\Throwable $e){
            echo json_encode(false);
            return;
        }
    }

    /**
     *
     * @return void
     * @author Fabian Müller
     */
    public function JailBuyoutAction(){
        try{
            $game = GameFactory::getActiveGame($this->em, $this->sessionId);
            $player = PlayerFactory::getActivePlayer($this->em, $game);
            $player->jailBuyout($this->em);
            $this->em->flush();
            $response = [
                "escaped" => true,
                "success" => true,
                "id" => $this->sessionId,
            ];
        }catch(\Throwable $e){
            $response = [
                "escaped" => false,
                "success" => "false",
                "error" => $e->getMessage()
            ];
        }
        echo json_encode($response);
    }
}