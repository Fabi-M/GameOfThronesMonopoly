<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Testcontroller extends BaseController
{
    public function TestAction(){
        echo $this->twig->render("Game/Views/Single-Card.html.twig",
            [
            ]);
    }

    public function EndTurn(){

    }
}