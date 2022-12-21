<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Renetestcontroller extends BaseController
{
    public function TestAction(){
        echo $this->twig->render(
            "Game/Views/Game.html.twig",
            [
                'imgPath' => self::IMG_PATH,
                'playFieldJPG' => 'Playfield.jpg',
                'figureDir' => '/figures/',
                'figurePNGName' => 'figur',
                'playerFigures' => 4
            ]
        );
    }

}