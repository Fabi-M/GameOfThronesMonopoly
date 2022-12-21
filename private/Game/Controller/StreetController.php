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
        $success = $streetService->buyStreet();
        $this->em->flush();
        echo json_encode($success);
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
        $streetService->buyHouse($fieldId);
        $this->em->flush();
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
        $streetService->sellHouse($fieldId);
        $this->em->flush();
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
        $streetService = new StreetService($game, $this->em);
        // checken wieviel miete
        $rent = $streetService->getRentforStreet($fieldId); // 0 oder mehr
        $player = PlayerFactory::getActivePlayer($this->em, $game);
        // abziehen
        $player->payMoney($rent);
        $isGameOver = $player->isGameOver();
        $this->em->flush();

        echo json_encode([
                             'totalMoney' => $player->getPlayerEntity()->getMoney(),
                             'isGameOver' => $isGameOver,
                             'payedRent' => $rent
                         ]);
    }
}