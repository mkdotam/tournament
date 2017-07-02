<?php

namespace AppBundle\DBAL;

use AppBundle\Manager\RankManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumRankType extends Type
{
    const ENUM_TITLE = 'enum_rank';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('".implode("', '", RankManager::getAvailableRanks())."')";
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, RankManager::getAvailableRanks())) {
            throw new \InvalidArgumentException("Invalid Rank key");
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