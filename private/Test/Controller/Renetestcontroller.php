<?php

namespace GameOfThronesMonopoly\Test\Controller;

use GameOfThronesMonopoly\Core\Controller\BaseController;

class Renetestcontroller extends BaseController
{
    public function TestAction(){

        $this->scriptCollector->addBottom('/js/Core/ModalDialog.js');
        $this->scriptCollector->addBottom('/js/Core/Ajax.js');
        $this->scriptCollector->addBottom('/js/Core/Events.js');
        $this->scriptCollector->addBottom('/js/Dice.js');
        $this->styleSheetCollector->addBottom('/css/GameStyle.css');
        echo $this->twig->render("Game/Views/Card-Pop-Up.html.twig",
            [
                'imgPath'=>self::IMG_PATH.'Playfield.jpg'
            ]);
    }

}