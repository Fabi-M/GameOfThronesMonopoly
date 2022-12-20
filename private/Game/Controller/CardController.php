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
?>
        <div class="container text-center">
        <div class="row">
            <div class="col single-card-template">
                {% include "Game/Views/Single-Card.html.twig" %}
            </div>
        </div>
        <div class="row interaktionsbuttons">
            <div class="row">
              <input class="interaktionsbutton dice" type="button" id="wuerfeln" data-action="Move" name ="wuerfeln" value="Würfeln">
            </div>
            <div class="row">
              <input class="interaktionsbutton" type="button" id="verkaufen" name ="verkaufen" value="Straße verkaufen">
            </div>
            <div class="row">
              <input class="interaktionsbutton" type="button" id="haus_kaufen" name ="haus_kaufen" value="Haus kaufen">
            </div>
            <div class="row">
              <input class="interaktionsbutton" type="button" id="hotel_kaufen" name ="hotel_kaufen" value="Hotel kaufen">
            </div>
            <div class="row">
              <input class="interaktionsbutton" type="button" id="handelsmenue_oeffnen" name ="handelsmenue_oeffnen" value="Handelsmenü öffnen">
            </div>
          </div>
    </div>  <?php  }


}