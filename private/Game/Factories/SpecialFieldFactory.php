<?php

namespace GameOfThronesMonopoly\Game\Factories;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\Specialfield as specialFieldEntity;
use GameOfThronesMonopoly\Game\Model\SpecialField;
use ReflectionException;

/**
 * Class SpecialFieldsFactory
 * @author Selina Stöcklein
 * @date   20.12.2022
 */
class SpecialFieldFactory
{
    const SPECIAL_FIELD_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:Specialfield';

    /**
     * @param EntityManager $em
     * @param int           $playFieldId
     * @return SpecialField|null
     * @throws ReflectionException
     * @author Selina Stöcklein
     */
    public static function getByFieldId(EntityManager $em, int $playFieldId)
    {
        /** @var specialFieldEntity $entity */
        $entity = $em->getRepository(self::SPECIAL_FIELD_NAMESPACE)->findOneBy(
            [
                'WHERE' => [
                    ['playFieldId', 'equal', $playFieldId]
                ]
            ]
        );

        if (empty($entity)) {
            return null;
        }
        return new SpecialField($entity);
    }
}