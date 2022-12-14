<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Renetestcontroller extends BaseController
{
    public function TestAction(){

        echo $this->twig->render("Game/Views/Game.html.twig",
            [
                'imgPath'=>__DIR__.'\..\..\..\public\img\Playfield.jpg'
            ]);
    }
}