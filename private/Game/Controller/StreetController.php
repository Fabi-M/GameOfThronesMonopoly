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
        $success = $streetService->buyStreet();
        $this->em->flush();
        echo json_encode($success);
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
     * @author Fabian Müller
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function BuyHouseAction($fieldId){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $streetService = new StreetService($game, $this->em);
        $streetService->buyHouse($fieldId);
        $this->em->flush();
        //Here the Game Logic
    }

    /**
     * Sell a house on the selected Street
     * @url /street/house/sell
     * @author Christian Teubner
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function SellHouseAction(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);

        //Here the Game Logic
    }

    /**
     * Buy a hotel on the selected Street
     * @url /street/hotel/buy
     * @author Christian Teubner
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function BuyHotelAction(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);

        //Here the Game Logic
    }

    /**
     * Sell a hotel on the selected Street
     * @url /street/house/sell
     * @author Christian Teubner
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function SellHotelAction(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);

        //Here the Game Logic
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