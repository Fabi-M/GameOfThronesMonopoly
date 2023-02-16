<?php

namespace GameOfThronesMonopoly\Game\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Game\Model\Factory;
use GameOfThronesMonopoly\Game\Model\Street;
use GameOfThronesMonopoly\Game\Factories\PlayFieldFactory;
use GameOfThronesMonopoly\Game\Model\Trainstation;
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
     * @author Christian Teubner Fabian Müller
     */
    public function ViewCardAction()
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $playFieldId = $_POST['playFieldId'];
        $card = PlayFieldFactory::getPlayField($this->em, $playFieldId, null);


        $type='Action';
        if($card instanceof Trainstation && $card->getStreetEntity()->getColor() == 'trainstation') {
            $type = 'Trainstation';
        }elseif($card instanceof Factory && $card->getStreetEntity()->getColor() == 'factory') {
            $type = 'Factory';
        }else if($card instanceof Street) {
        $type = 'Street';
        }
        
        echo $this->twig->render('Game/Views/CardInfoPopup.html.twig' ,
        [
            'card' => $card,
            'cardType' => $type,
            'imgPath' => self::IMG_PATH,
        ]);

    }

    public function ViewPlayerCardAction()
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);

        echo $this->twig->render('Game/Views/PlayerCardInfoPopUp.html.twig',
        [
        ]);
    }
    


}