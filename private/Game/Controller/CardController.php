<?php

namespace GameOfThronesMonopoly\Game\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;
use GameOfThronesMonopoly\Game\Entities\player;
use GameOfThronesMonopoly\Game\Model\Street;
use GameOfThronesMonopoly\Game\Model\SpecialField;
use GameOfThronesMonopoly\Game\Factories\PlayFieldFactory;
use GameOfThronesMonopoly\Game\Service\GameService;

class CardController extends BaseController
{
    /**
     * View a Card as Popup
     * @url    /Card/View
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @author Christian Teubner
     */
    public function ViewCardAction()
    {
        $gameService = new GameService();
        $game = $gameService->getGameBySessionId($this->em, $this->sessionId);
        $playFieldId = $_POST['playFieldId'];
        $card = PlayFieldFactory::getPlayField($this->em, $playFieldId, null);


        $type='Action';
        if($card instanceof Street) {
            $type = 'Street';
        } elseif($card instanceof SpecialField && $card->getEntity()->getAction() == 'trainstation') {
            $type = 'Trainstation';
        }
        
        echo $this->twig->render('Game/views/CardInfoPopUp.html.twig' ,
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

        echo $this->twig->render('Game/views/PlayerCardInfoPopUp.html.twig', 
        [
        ]);
    }
    


}