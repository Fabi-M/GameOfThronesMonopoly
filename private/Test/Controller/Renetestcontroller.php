<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Renetestcontroller extends BaseController
{
    public function TestAction(){
        echo $this->twig->render(
            "Game/views/Single-Card-Werke.html.twig",
            [
                'imgPath'=>self::IMG_PATH.'menu/monopoly-title.jpg',
                'imgPathTrennlinie'=>self::IMG_PATH.'menu/trennlinie.png'
            ]
        );
    }

}