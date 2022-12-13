<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Testcontroller extends BaseController
{
    public function TestAction(){
        $this->styleSheetCollector->addBottom('css/GameStyle.css');
    echo $this->twig->render("Game/views/Game.html.twig",
            [
            ]);
    }
}