<?php

namespace GameOfThronesMonopoly\Game\Controller;

use Exception;
use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Core\Exceptions\SQLException;
use GameOfThronesMonopoly\Game\Factories\GameFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Service\GameService;
use GameOfThronesMonopoly\Game\Service\StreetService;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class StreetController extends BaseController
{
    /**
     * Buys the selected Street
     * @url    /street/buy
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function BuyStreetAction()
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $streetService = new StreetService($game, $this->em);
        $response = $streetService->buyStreet();
        $this->em->flush();
        echo json_encode($response);
    }

    /**
     * Sell the selected street
     * @url    /street/sell/(.*)
     * @param $fieldId
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function SellStreetAction($fieldId): void
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $streetService = new StreetService($game, $this->em);
        $success = $streetService->sellStreet($fieldId);
        $this->em->flush();
        echo json_encode($success);
    }

    /**
     * Buy a house on the selected Street
     * @url    /street/house/buy/(.*)
     * @param $fieldId
     * @return void
     * @throws SQLException
     * @throws Exception
     * @author Fabian Müller
     */
    public function BuyHouseAction($fieldId): void
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $streetService = new StreetService($game, $this->em);
        $response = $streetService->buyHouse($fieldId);
        $this->em->flush();
        echo json_encode($response);
    }

    /**
     * Sell a house on the selected Street
     * @url    /street/house/sell/(.*)
     * @param $fieldId
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function SellHouseAction($fieldId): void
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $streetService = new StreetService($game, $this->em);
        $response = $streetService->sellHouse($fieldId);
        $this->em->flush();
        echo json_encode($response);
    }

    /**
     * Trade the selected Street to another Player
     * @url    /street/trade
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @author Christian Teubner
     */
    public function TradeStreetAction(): void
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        //Here the Game Logic
    }

    public function PayRentAction(): void
    {
        $fieldId = $_POST['playFieldId'];
        $game = GameFactory::getActiveGame($this->em, $this->sessionId);
        try {
            // checken wieviel miete
            $player = PlayerFactory::getActivePlayer($this->em, $game);
            $street = StreetFactory::getByFieldId($this->em, $fieldId);
            $owner = PlayerFactory::getPlayerById(
                $this->em, $street->getXField()->getPlayerXFieldEntity()->getPlayerId()
            );
            $rent = $street->getRent(); // 0 oder mehr
            // abziehen
            $player->changeBalance(-($rent));
            $owner->changeBalance($rent);
            $isGameOver = $player->isGameOver();
            $this->em->flush();
            $totalMoney = $player->getPlayerEntity()->getMoney();
        } catch (Throwable $e) {
            $totalMoney = 0;
            $rent = 0;
            $isGameOver = true;
        }

        echo json_encode([
                             'totalMoney' => $totalMoney,
                             'isGameOver' => $isGameOver,
                             'payedRent' => $rent
                         ]);
    }
}