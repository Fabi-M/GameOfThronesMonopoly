<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Renetestcontroller extends BaseController
{
    public function TestAction(){
        var_dump($this->sessionId);
        var_dump(__DIR__.'\..\..\..\public\img\Playfield.jpg');

        echo $this->twig->render("Game/Views/Game.html.twig",
            [
                'imgPath'=>__DIR__.'\..\..\..\public\img\Playfield.jpg'
            ]);
    }
}