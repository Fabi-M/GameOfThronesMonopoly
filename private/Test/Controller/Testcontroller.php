<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Testcontroller extends BaseController
{
    public function SelinaTestAction()
    {
        echo $this->twig->render(
            "Game/views/Start-Page.html.twig",
            [
                'imgPath'=>self::IMG_PATH.'menu/monopoly-title.jpg',
                'imgPathTrennlinie'=>self::IMG_PATH.'menu/trennlinie.png'
            ]
        );
    }

    public function EndTurn()
    {
    }
}