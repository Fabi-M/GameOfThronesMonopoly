<?php

namespace GameOfThronesMonopoly\Game\Controller;

use Exception;
use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Game\Service\GameService;
use GameOfThronesMonopoly\Game\Service\StreetService;

class StreetController extends BaseController
{

    /**
     * Buys the selected Street
     * @url /street/buy
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function BuyStreetAction(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $streetService = new StreetService($game, $this->em);
        $response = $streetService->buyStreet();
        $this->em->flush();
        echo json_encode($response);
    }

    /**
     * Sell the selected street
     * @url /street/sell/(.*)
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function SellStreetAction($fieldId){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $streetService = new StreetService($game, $this->em);
        $success = $streetService->sellStreet($fieldId);
        $this->em->flush();
        echo json_encode($success);
    }

    /**
     * Buy a house on the selected Street
     * @url /street/house/buy/(.*)
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function BuyHouseAction($fieldId){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $streetService = new StreetService($game, $this->em);
        $streetService->buyHouse($fieldId);
        $this->em->flush();
    }

    /**
     * Sell a house on the selected Street
     * @url /street/house/sell/(.*)
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function SellHouseAction($fieldId){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $streetService = new StreetService($game, $this->em);
        $streetService->sellHouse($fieldId);
        $this->em->flush();
    }

    /**
     * Trade the selected Street to another Player
     * @url /street/trade
     * @author Christian Teubner
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function TradeStreetAction(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);

        //Here the Game Logic
    }

}