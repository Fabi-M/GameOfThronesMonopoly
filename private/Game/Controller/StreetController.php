<?php

namespace GameOfThronesMonopoly\Game\Controller;

use Exception;
use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Core\Exceptions\SQLException;
use GameOfThronesMonopoly\Game\Factories\GameFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Model\Street;
use GameOfThronesMonopoly\Game\Service\GameService;
use GameOfThronesMonopoly\Game\Service\StreetService;
use ReflectionException;
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
        try {
            $gameService = new GameService();
            $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
            $streetService = new StreetService($game, $this->em);
            $response = $streetService->buyStreet();
            $this->em->flush();
        } catch (Throwable $e) {
            $response = [
                "success" => false,
                "error" => $e->getMessage(),
            ];
        }

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
        try {
            $gameService = new GameService();
            $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
            $streetService = new StreetService($game, $this->em);
            $response = $streetService->sellStreet($fieldId);
            $this->em->flush();
        } catch (Throwable $e) {
            $response = [
                "success" => false,
                "error" => $e->getMessage(),
            ];
        }

        echo json_encode($response);
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
        try {
            $gameService = new GameService();
            $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
            $streetService = new StreetService($game, $this->em);
            $response = $streetService->buyHouse($fieldId, $this->em);
            $this->em->flush();
        } catch (Throwable $e) {
            $response = [
                "success" => false,
                "error" => $e->getMessage(),
            ];
        }

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
        try {
            $gameService = new GameService();
            $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
            $streetService = new StreetService($game, $this->em);
            $response = $streetService->sellHouse($fieldId, $this->em);
            $this->em->flush();
        } catch (Throwable $e) {
            $response = [
                "success" => false,
                "error" => $e->getMessage(),
            ];
        }

        echo json_encode($response);
    }

    /**
     * Trade the selected Street to another Player
     * @url    /street/trade
     * @return void
     * @author Christian Teubner
     */
    public function TradeStreetAction(): void
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        //Here the Game Logic
    }

    /**
     * @url    /Street/Rent/Pay
     * @return void
     * @author Selina Stöcklein
     */
    public function PayRentAction(): void
    {
        $fieldId = $_POST['playFieldId'];
        $msg = '';
        try {
            $game = GameFactory::getActiveGame($this->em, $this->sessionId);
            // checken wieviel miete
            $player = PlayerFactory::getActivePlayer($this->em, $game);
            $street = StreetFactory::getByFieldId($this->em, $fieldId, $game->getGameEntity()->getId());
            if (!($street instanceof Street) || $street->isOwned()) {
                throw new Exception("No Rent To Pay");
            }
            $owner = PlayerFactory::getPlayerById(
                $this->em, $street->getXField()->getPlayerXFieldEntity()->getPlayerId()
            );
            $rent = $player->payRentTo($owner, $street, $this->em);
            $isGameOver = $player->isGameOver();
            $totalMoney = $player->getPlayerEntity()->getMoney();
            $this->em->flush();
        } catch (Throwable $e) {
            $msg = $e->getTraceAsString();
            $totalMoney = 0;
            $rent = 0;
            $isGameOver = false;
        }

        echo json_encode(
            [
                'info' => $msg,
                'totalMoney' => $totalMoney,
                'isGameOver' => $isGameOver,
                'payedRent' => $rent
            ]
        );
    }
}