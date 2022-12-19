<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Testcontroller extends BaseController
{

    public function SelinaTestAction()
    {
        $this->scriptCollector->addBottom('/js/Core/ModalDialog.js');
        $this->scriptCollector->addBottom('/js/Core/Ajax.js');
        $this->scriptCollector->addBottom('/js/Core/Events.js');
        $this->scriptCollector->addBottom('/js/Figure.js');
        $this->scriptCollector->addBottom('/js/FigureService.js');
        $this->scriptCollector->addBottom('/js/PlayFieldService.js');
        $this->scriptCollector->addBottom('/js/Dice.js');
        echo $this->twig->render(
            "Game/Views/Game.html.twig",
            [
                'imgPath' => self::IMG_PATH,
                'playFieldJPG' => 'Playfield.jpg',
                'figureDir'=>'/figures/',
                'figurePNGName'=>'figur',
                'playerFigures' => 4
            ]
        );
    }

    public function EndTurn()
    {
    }
}