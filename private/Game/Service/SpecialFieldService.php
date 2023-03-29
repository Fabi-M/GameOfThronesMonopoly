<?php


namespace GameOfThronesMonopoly\Game\Service;


use GameOfThronesMonopoly\Game\Factories\SpecialFieldFactory;

class SpecialFieldService
{

    private $specialFields = [0,2,4,7,10,12,17,20,22,28,30,33,36,38]; // todo ask db for ids
    private $specialField;
    private $player;
    private $em;

    public function checkIfOnSpecialField($em, $fieldId, $player){
        $this->player = $player;
        $this->em = $em;
        if(in_array($fieldId, $this->specialFields)){
            $this->specialField = SpecialFieldFactory::getByFieldId($em, $fieldId);
            if($this->specialField != null){
                $functionName = $this->specialField->getEntity()->getAction(); // action
                 $this->$functionName();
                return [
                    "comment" => $this->specialField->getEntity()->getComment(),
                    "amount" => $this->specialField->getEntity()->getAmount()];
            }
        }
        return [];
    }

    public function moneyChange(){
        $this->player->changeBalance($this->specialField->getEntity()->getAmount(), $this->em);
    }

    public function draw(){
        // todo logic to draw card
    }

    public function prison(){
        $this->player->goToJail($this->em);
        $this->player->move($this->em, [], $this->specialField->getEntity()->getAmount());
    }
}