<?php

namespace GameOfThronesMonopoly\Game\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Game\Service\GameService;

class CardController extends BaseController
{

    /**
     * View a Card as Popup
     * @url /Card/View
     * @author Christian Teubner
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function ViewCardAction(){
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);

        //Here the Game Logic
    }

}