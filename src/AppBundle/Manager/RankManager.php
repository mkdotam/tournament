<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Rank;
use Symfony\Component\Yaml\Yaml;

class RankManager
{
    public static function getRankByKey($rankKey)
    {
        if (!in_array($rankKey, self::getAvailableRanks())) {
            throw new \InvalidArgumentException();
        }

        $ranks = self::readRanksFromFile();

        $rank = new Rank();
        $rank->setKey($rankKey);
        $rank->setTitle($ranks[$rankKey]['title']);
        $rank->setMoves($ranks[$rankKey]['moves']);
        $rank->setRequiredPoints($ranks[$rankKey]['requiredPoints']);
        
        return $rank;
    }

    /**
     * @return string
     */
    public static function getInitialRank()
    {
        $ranks = array_keys(self::readRanksFromFile());

        return $ranks[0];
    }

    /**
     * @return array
     */
    public static function getAvailableRanks()
    {
        return array_keys(self::readRanksFromFile());
    }

    /**
     * @return array
     */
    public static function readRanksFromFile()
    {
        $ranks = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/ranks.yml'));

        foreach ($ranks as $rank) {
            if (!isset($rank['title'])
                or !isset($rank['moves'])
                or !isset($rank['requiredPoints'])
            ) {
                throw new \Exception("Check our Yaml file for ranks");
            }
        }

        return $ranks;
    }

    /**
     * @param $points
     * @return Rank|null
     */
    public static function getRankByPoints($points)
    {
        $ranks = self::readRanksFromFile();
        $newRank = null;

        foreach ($ranks as $key => $rank) {
            if ($points >= $rank['requiredPoints']) {
                $newRank = self::getRankByKey($key);
            }
        }
        return $newRank;
    }


}