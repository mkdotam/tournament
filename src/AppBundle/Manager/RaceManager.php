<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Race;
use Symfony\Component\Yaml\Yaml;

class RaceManager
{
    public static function getRaceByKey($raceKey)
    {
        if (!in_array($raceKey, self::getAvailableRaces())) {
            throw new \InvalidArgumentException();
        }
     
        $races = self::readRacesFromFile();

        $race = new Race();   
        $race->setKey($raceKey);
        $race->setTitle($races[$raceKey]['title']);
        $race->setAttackPower($races[$raceKey]['attack']);
        $race->setDefensePower($races[$raceKey]['defense']);
    

        return $race;
    }

    /**
     * @return array
     */
    public static function getAvailableRaces()
    {
        return array_keys(self::readRacesFromFile());
    }

    /**
     * @return array
     */
    public static function readRacesFromFile()
    {
        $races = Yaml::parse(file_get_contents(__DIR__ . '/../Resources/config/races.yml'));

        foreach ($races as $race) {
            if (!isset($race['title'])
                or !isset($race['attack'])
                or !isset($race['defense'])
            ) {
                throw new \Exception("Check our Yaml file for races");
            }
        }

        return $races;
    }

}