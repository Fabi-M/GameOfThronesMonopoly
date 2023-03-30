<?php

namespace GameOfThronesMonopoly\Game\Controller;

use Exception;
use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Game\Factories\GameFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Model\Dice;
use GameOfThronesMonopoly\Game\Service\GameService;
use GameOfThronesMonopoly\Game\Service\SaveGameService;
use GameOfThronesMonopoly\Game\Service\ScoreService;
use Throwable;

class GameManagerController extends BaseController
{
    public function InitAction(): void
    {
        try {
            // get game
            $game = GameFactory::getActiveGame($this->em, $this->sessionId);
            if ($game === null) {
                if ($_SERVER['REMOTE_ADDR'] == "::1") {
                    header("Location: http://localhost/GameOfThronesMonopoly/Homepage");
                }
                else {
                    header("Location: http://178.254.31.157/GameOfThronesMonopoly/Homepage");
                }
                die();
            }
            // get players
            $players = PlayerFactory::getPlayersOfGame($this->em, $game);
            $service = new SaveGameService();
            if (count($players) > 0) {
                $saveGameConfig = $service->getPlayfieldInitConfig($game, $players, $this->em);
            }
            else
            {
                echo "BOOOOOOOMMMMMMM";
                return;
            }

            // add dice
            $this->styleSheetCollector->addBottom('/css/Dice.css');
            $this->scriptCollector->addBottom('/js/Score.js');
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
        $response = $activePlayer->move($this->em, $rolled);
        if ($rolled[0] !== $rolled[1]) { // not for pasch
            // save that player rolled for movement
            $gameService->checkIfAllowedToEndTurn($rolled);
            $game->getGameEntity()->setRolledDice(1);
            $this->em->persist($game->getGameEntity());
        }else{
            $paschCount = $game->getGameEntity()->getPaschCount();
            $paschCount++;
            $game->getGameEntity()->setPaschCount($paschCount);
            if($paschCount == 3){
                $activePlayer->goToJail($this->em);
                $game->getGameEntity()->setAllowedToEndTurn(1);
                $response["playFieldId"] = 10;
            }
            $this->em->persist($game->getGameEntity());
        }
        // save
        $this->em->flush();
        $response["dice"] = $rolled;
        $response["money"] = $activePlayer->getPlayerEntity()->getMoney();
        $response["activePlayerId"] = $game->getGameEntity()->getActivePlayerId();
        echo json_encode($response);
    }

    /**
     * Rolls the Dice for Escaping
     * @url    /Roll/Escape
     * @return void
     * @throws Exception
     * @author Christian Teubner, Selina Stöcklein, Fabian Müller
     */
    public function RollForEscapeAction(): void
    {
        try{
            $gameService = new GameService();
            $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
            $player = PlayerFactory::getActivePlayer($this->em, $game);
            if($player->getPlayerEntity()->getJailRolls() == 3){
                Throw new Exception("You are only allowed to roll 3 times, you have to pay the buyout fee now");
            }
            $dice = new Dice();
            $rolled = $dice->roll();
            $player->hasRolledForEscape($rolled);
            $game->getGameEntity()->setAllowedToEndTurn(1);
            $this->em->persist($game->getGameEntity());
            $this->em->persist($player->getPlayerEntity());
            $this->em->flush();
            $response =  json_encode(
                [
                    'dice' => $rolled,
                    'inJail' => $rolled[0] != $rolled[1],
                    'activePlayerId' => $game->getGameEntity()->getActivePlayerId(),
                    "money" => $player->getPlayerEntity()->getMoney()
                ]
            );
        }catch(\Throwable $e){
            $response =  json_encode(
                [
                    'inJail' => true,
                    'activePlayerId' => $game->getGameEntity()->getActivePlayerId(),
                    'error' => $e->getMessage()
                ]
            );
        }
        echo $response;
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
            "Game/Views/Start-Page.html.twig",
            [
                'imgPath' => self::IMG_PATH . 'menu/monopoly-title.jpg',
                'imgPathTrennlinie' => self::IMG_PATH . 'menu/trennlinie.png'
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
    public function CanLoadGameAction()
    {
        try {
            $game = GameFactory::getActiveGame($this->em, $this->sessionId);
            if ($game === null) {
                echo json_encode(false);
                return;
            }
            echo json_encode(true);
            return;
        } catch (\Throwable $e) {
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
            $game->getGameEntity()->setPaschCount(0);
            $this->em->persist($game->getGameEntity());
            $this->em->flush();
            $response = [
                "inJail" => false,
                "success" => true,
                "id" => $this->sessionId,
                "money" => $player->getPlayerEntity()->getMoney()
            ];
        }catch(\Throwable $e){
            $response = [
                "inJail" => true,
                "success" => "false",
                "error" => $e->getMessage()
            ];
        }
        echo json_encode($response);
    }

    public function GetEndScoreAction()
    {
        try {
            $game = GameFactory::getActiveGame($this->em, $this->sessionId);
            $players = PlayerFactory::getPlayersOfGame($this->em, $game);
            $scoreService = new ScoreService();
            $heading = 'GAME OVER <br> Der aktuelle Spieler ist pleite gegangen.';
            $msg = "Danke euch für's spielen! <br> Credits an René, Fabian, Christian und Selina.";
            $playerScores = $scoreService->calculateScore($players, $game, $this->em);
        } catch (Throwable $e) {
            $playerScores = [];
            $heading = 'Oh no, fail :(';
            $error = $e->getMessage() . ' --- ' . $e->getTraceAsString();
            error_log($error);
        }

        echo $this->twig->render('Game/Views/ScoreBoard.html.twig', [
            'scores' => $playerScores,
            'heading' => $heading,
            'msg' => $msg,
            'IMG_PATH' => self::IMG_PATH
        ]);
    }

    public function EndGameAction()
    {
        try {
            $game = GameFactory::getActiveGame($this->em, $this->sessionId);
            $game->getGameEntity()->setGameOver(1);
            $this->em->persist($game->getGameEntity());
            $this->em->flush();
            session_regenerate_id(true);
            $this->sessionId = session_id();
            $result = true;
        } catch (Throwable $e) {
            $result = false;
        }
        echo json_encode($result);
    }
}