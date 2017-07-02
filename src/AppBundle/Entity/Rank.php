<?php

namespace AppBundle\Entity;

use AppBundle\Manager\RankManager;
use Symfony\Component\Yaml\Yaml;

class Rank
{
    protected $key;
    protected $title;
    protected $moves;
    protected $requiredPoints;

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
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getMoves()
    {
        return $this->moves;
    }

    /**
     * @return mixed
     */
    public function setMoves($value)
    {
        $this->moves = $value;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getRequiredPoints()
    {
        return $this->requiredPoints;
    }

    /**
     * @return mixed
     */
    public function setRequiredPoints($value)
    {
        $this->requiredPoints = $value;
        return $this;
    }
}