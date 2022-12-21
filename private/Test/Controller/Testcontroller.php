<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Testcontroller extends BaseController
{
    public function SelinaTestAction()
    {
        $this->scriptCollector->addBottom('/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js');
        $this->scriptCollector->addBottom('/node_modules/bootstrap/dist/js/bootstrap.bundle.js');
        $this->scriptCollector->addBottom('/node_modules/bootstrap/dist/js/bootstrap.min.js');
        $this->scriptCollector->addBottom('/js/Core/ModalDialog.js');
        $this->scriptCollector->addBottom('/js/Core/Ajax.js');
        $this->scriptCollector->addBottom('/js/Core/Events.js');
        $this->scriptCollector->addBottom('/js/Figure.js');
        $this->scriptCollector->addBottom('/js/FigureService.js');
        $this->scriptCollector->addBottom('/js/PlayFieldService.js');
        $this->scriptCollector->addBottom('/js/Dice.js');
        $this->scriptCollector->addBottom('/js/PlayField.js');
        $this->scriptCollector->addBottom('/js/Round.js');
        $this->styleSheetCollector->addBottom(
            '/node_modules/bootstrap/dist/css/bootstrap.min.css'
        );
        $this->styleSheetCollector->addBottom('/css/GameStyle.css');
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

    public function EndTurn()
    {
    }
}