<?php

namespace AppBundle\DBAL;

use AppBundle\Manager\RaceManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumRaceType extends Type
{
    const ENUM_TITLE = 'enum_race';
    const ELF = 'elf';
    const DWARF = 'dwarf';
    const HOBBIT = 'hobbit';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('".implode("', '", RaceManager::getAvailableRaces())."')";
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, RaceManager::getAvailableRaces())) {
            throw new \InvalidArgumentException("Invalid Race key");
        }

        return $value;
    }

    public function getName()
    {
        return self::ENUM_TITLE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}