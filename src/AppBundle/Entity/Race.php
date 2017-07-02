<?php

namespace AppBundle\Entity;

use AppBundle\Manager\RaceManager;

class Race
{
    private $key;
    private $title;
    private $attack;
    private $defense;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function setKey($value)
    {
        $this->key = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getAttackPower()
    {
        return $this->attack;
    }

    /**
     * @return int
     */
    public function setAttackPower($value)
    {
        $this->attack = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefensePower()
    {
        return $this->defense;
    }

        /**
     * @return int
     */
    public function setDefensePower($value)
    {
        $this->defense = $value;
        return $this;
    }
}