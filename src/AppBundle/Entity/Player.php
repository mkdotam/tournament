<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Race;
use AppBundle\Entity\Rank;
use Symfony\Component\Serializer\Annotation\Groups;
use AppBundle\Manager\RaceManager;
use AppBundle\Manager\RankManager;

/**
 * Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"leaderboard", "myinfo"})
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @Groups({"myinfo"})
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var string
     *
     * @Groups({"leaderboard", "myinfo"})
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Groups({"leaderboard", "myinfo"})
     * @ORM\Column(name="motto", type="string", length=255)
     */
    private $motto;

    /**
     * @var string
     *
     * @Groups({"leaderboard", "myinfo"})
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;

    /**
     * @var string
     *
     * @Groups({"leaderboard", "myinfo"})
     * @ORM\Column(name="race", type="enum_race", length=255)
     */
    private $race;

    /**
     * @var string
     *
     * @Groups({"leaderboard", "myinfo"})
     * @ORM\Column(name="rank", type="enum_rank", length=255)
     */
    private $rank;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->points = 0;
    }

    public function getMoves()
    {
        return $this->getRank()->getMoves();
    }

    public function getAttackPower()
    {
        return $this->getRace()->getAttackPower();
    }

    public function getDefensePower()
    {
        return $this->getRace()->getDefensePower();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Player
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set motto
     *
     * @param string $motto
     * @return Player
     */
    public function setMotto($motto)
    {
        $this->motto = $motto;

        return $this;
    }

    /**
     * Get motto
     *
     * @return string 
     */
    public function getMotto()
    {
        return $this->motto;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return Player
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set race
     *
     * @param string $race
     * @return Player
     */
    public function setRace($race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return Race
     */
    public function getRace()
    {
        return RaceManager::getRaceByKey($this->race);
    }

    /**
     * Set rank
     *
     * @param string $rank
     * @return Player
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return Rank
     */
    public function getRank()
    {
        return RankManager::getRankByKey($this->rank);
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Player
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Player
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
