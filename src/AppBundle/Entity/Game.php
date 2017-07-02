<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use AppBundle\Manager\RankManager;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
{
    /**
     * @var int
     *
     * @Groups({"default", "finished"})
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @Groups({"default", "finished"})
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @Groups({"finished"})
     * @ORM\Column(name="weatherFactor", type="integer", nullable=true)
     */
    private $weatherFactor;

    /**
     * @var string
     *
     * @Groups({"default", "finished"})
     * @ORM\Column(name="rank", type="enum_rank", length=255)
     */
    private $rank;

    /**
     * @var int
     *
     * @Groups({"default", "finished"})
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="player_one")
     */
    private $playerOne;

    /**
     * @var int
     *
     * @Groups({"finished"})
     * @ORM\Column(name="playerOneAttackCount", type="integer", nullable=true)
     */
    private $playerOneAttackCount;

    /**
     * @var int
     *
     * @Groups({"finished"})
     * @ORM\Column(name="playerOneDefenseCount", type="integer", nullable=true)
     */
    private $playerOneDefenseCount;

    /**
     * @var string
     *
     * @Groups({"default", "finished"})
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="player_two")
     */
    private $playerTwo;

    /**
     * @var int
     *
     * @Groups({"finished"})
     * @ORM\Column(name="playerTwoAttackCount", type="integer", nullable=true)
     */
    private $playerTwoAttackCount;

    /**
     * @var int
     *
     * @Groups({"finished"})
     * @ORM\Column(name="playerTwoDefenseCount", type="integer", nullable=true)
     */
    private $playerTwoDefenseCount;

    /**
     * @var bool
     *
     * @Groups({"default", "finished"})
     * @ORM\Column(name="isFinished", type="boolean", nullable=true)
     */
    private $isFinished;

    /**
     * @var string
     *
     * @Groups({"finished"})
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="winner", nullable=true)
     */
    private $winner;
    

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->isFinished = false;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Game
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set weatherFactor
     *
     * @param integer $weatherFactor
     *
     * @return Game
     */
    public function setWeatherFactor($weatherFactor)
    {
        $this->weatherFactor = $weatherFactor;

        return $this;
    }

    /**
     * Get weatherFactor
     *
     * @return integer
     */
    public function getWeatherFactor()
    {
        return $this->weatherFactor;
    }

    /**
     * Set rank
     *
     * @param enum_rank $rank
     *
     * @return Game
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return enum_rank
     */
    public function getRank()
    {
        return RankManager::getRankByKey($this->rank);
    }

    /**
     * Set playerOneAttackCount
     *
     * @param integer $playerOneAttackCount
     *
     * @return Game
     */
    public function setPlayerOneAttackCount($playerOneAttackCount)
    {
        $this->playerOneAttackCount = $playerOneAttackCount;

        return $this;
    }

    /**
     * Get playerOneAttackCount
     *
     * @return integer
     */
    public function getPlayerOneAttackCount()
    {
        return $this->playerOneAttackCount;
    }

    /**
     * Set playerOneDefenseCount
     *
     * @param integer $playerOneDefenseCount
     *
     * @return Game
     */
    public function setPlayerOneDefenseCount($playerOneDefenseCount)
    {
        $this->playerOneDefenseCount = $playerOneDefenseCount;

        return $this;
    }

    /**
     * Get playerOneDefenseCount
     *
     * @return integer
     */
    public function getPlayerOneDefenseCount()
    {
        return $this->playerOneDefenseCount;
    }

    /**
     * Set playerTwoAttackCount
     *
     * @param integer $playerTwoAttackCount
     *
     * @return Game
     */
    public function setPlayerTwoAttackCount($playerTwoAttackCount)
    {
        $this->playerTwoAttackCount = $playerTwoAttackCount;

        return $this;
    }

    /**
     * Get playerTwoAttackCount
     *
     * @return integer
     */
    public function getPlayerTwoAttackCount()
    {
        return $this->playerTwoAttackCount;
    }

    /**
     * Set playerTwoDefenseCount
     *
     * @param integer $playerTwoDefenseCount
     *
     * @return Game
     */
    public function setPlayerTwoDefenseCount($playerTwoDefenseCount)
    {
        $this->playerTwoDefenseCount = $playerTwoDefenseCount;

        return $this;
    }

    /**
     * Get playerTwoDefenseCount
     *
     * @return integer
     */
    public function getPlayerTwoDefenseCount()
    {
        return $this->playerTwoDefenseCount;
    }

    /**
     * Set isFinished
     *
     * @param boolean $isFinished
     *
     * @return Game
     */
    public function setIsFinished($isFinished)
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    /**
     * Get isFinished
     *
     * @return boolean
     */
    public function getIsFinished()
    {
        return $this->isFinished;
    }

    /**
     * Set playerOne
     *
     * @param \AppBundle\Entity\Player $playerOne
     *
     * @return Game
     */
    public function setPlayerOne(\AppBundle\Entity\Player $playerOne = null)
    {
        $this->playerOne = $playerOne;

        return $this;
    }

    /**
     * Get playerOne
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayerOne()
    {
        return $this->playerOne;
    }

    /**
     * Set playerTwo
     *
     * @param \AppBundle\Entity\Player $playerTwo
     *
     * @return Game
     */
    public function setPlayerTwo(\AppBundle\Entity\Player $playerTwo = null)
    {
        $this->playerTwo = $playerTwo;

        return $this;
    }

    /**
     * Get playerTwo
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayerTwo()
    {
        return $this->playerTwo;
    }

    /**
     * Set winner
     *
     * @param \AppBundle\Entity\Player $winner
     *
     * @return Game
     */
    public function setWinner(\AppBundle\Entity\Player $winner = null)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get winner
     *
     * @return \AppBundle\Entity\Player
     */
    public function getWinner()
    {
        return $this->winner;
    }
}
