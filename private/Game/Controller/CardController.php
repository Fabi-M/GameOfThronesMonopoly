<?php

namespace GameOfThronesMonopoly\Game\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Game\Entities\player;
use GameOfThronesMonopoly\Game\Factories\PlayFieldFactory;
use GameOfThronesMonopoly\Game\Service\GameService;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CardController extends BaseController
{
    /**
     * View a Card as Popup
     * @url    /Card/View
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @author Christian Teubner
     */
    public function ViewCardAction(): void
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);

        echo $this->twig->render('Game/views/CardInfoPopUp.html.twig');
    }

    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function ViewPlayerCardAction(): void
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);

        echo $this->twig->render('Game/views/PlayerCardInfoPopUp.html.twig', 
        [
            'imgPath' => self::IMG_PATH,
            'zug-bild' => 'Train.png'
        ]);


    }
    


}