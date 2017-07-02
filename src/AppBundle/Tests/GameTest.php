<?php

namespace AppBundle\Manager\Tests;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Entity\Rank;
use AppBundle\Manager\GameManager;
use AppBundle\Manager\PlayerManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;


class GameTest extends TestCase
{
    protected $attack = 3;

    /**
     * @var Player
     */
    protected $player1;

    /**
     * @var Player
     */
    protected $player2;

    /**
     * @var Rank
     */
    protected $rank;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var $playerManager
     */
    protected $playerManager;

    protected function setUp()
    {
        $this->rank = $this
            ->getMockBuilder(Rank::class)
            ->getMock();
        $this->rank->expects($this->any())
            ->method("getKey")
            ->willReturn("soldier");
        $this->rank->expects($this->any())
            ->method("getRequiredPoints")
            ->willReturn(12);
        $this->rank->expects($this->any())
            ->method("getMoves")
            ->willReturn(5);

        $this->player1 = $this->createMock(Player::class);
        $this->player1->expects($this->any())
            ->method('getMoves')
            ->willReturn($this->rank->getMoves());
        $this->player1->expects($this->any())
            ->method('getAttackPower')
            ->willReturn(7);
        $this->player1->expects($this->any())
            ->method('getDefensePower')
            ->willReturn(4);
        $this->player1->expects($this->any())
            ->method('getRank')
            ->willReturn($this->rank);
        $this->player1->expects($this->any())
            ->method('getName')
            ->willReturn("A");

        $this->player2 = $this->createMock(Player::class);
        $this->player2->expects($this->any())
            ->method('getMoves')
            ->willReturn($this->rank->getMoves());
        $this->player2->expects($this->any())
            ->method('getAttackPower')
            ->willReturn(5);
        $this->player2->expects($this->any())
            ->method('getDefensePower')
            ->willReturn(6);
        $this->player2->expects($this->any())
            ->method('getRank')
            ->willReturn($this->rank);
        $this->player2->expects($this->any())
            ->method('getName')
            ->willReturn("B");


        $game = $this->createMock(Game::class);
        $game->expects($this->any())
            ->method('getPlayerOne')
            ->willReturn($this->player1);
        $game->expects($this->any())
            ->method('getPlayerTwo')
            ->willReturn($this->player2);
        $game->expects($this->any())
            ->method('getRank')
            ->willReturn($this->rank->getKey());

        $gameRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $gameRepository->expects($this->any())
            ->method('find')
            ->willReturn($game);

        $this->entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($gameRepository);

        $this->playerManager = $this
            ->getMockBuilder(PlayerManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->playerManager->expects($this->any())
            ->method('registerWin')
            ->willReturn($this->player1);
    }

    public function testInitGame()
    {
        $gameManager= new GameManager($this->entityManager, $this->playerManager);
        $game = $gameManager->startGameForPlayer($this->player1, $this->attack);

        $defense = $this->rank->getMoves() - $this->attack;

        $this->assertEquals($this->rank->getKey(), $game->getRank()->getKey());
        $this->assertEquals($this->attack, $game->getPlayerOneAttackCount());
        $this->assertEquals($defense, $game->getPlayerOneDefenseCount());
    }

    public function testAddPlayerToGame()
    {
        $gameManager= new GameManager($this->entityManager, $this->playerManager);

        $game = $gameManager->startGameForPlayer($this->player1, $this->attack);
        $gameManager->addPlayerToGame($game, $this->player2, $this->attack);

        $defense = $this->rank->getMoves() - $this->attack;

        $this->assertEquals($this->attack, $game->getPlayerTwoAttackCount());
        $this->assertEquals($defense, $game->getPlayerTwoDefenseCount());
    }

    public function testFightDraw()
    {
        $gameManager= new GameManager($this->entityManager, $this->playerManager);
        $game = $gameManager->startGameForPlayer($this->player1, 2);
        $game = $gameManager->addPlayerToGame($game, $this->player2, 4);
        $game->setWeatherFactor(0);
        $game = $gameManager->fightGame($game);

        $this->assertEquals(true, $game->getIsFinished());
        $this->assertEquals(null, $game->getWinner());

    }

    public function testFightWinA()
    {
        $gameManager= new GameManager($this->entityManager, $this->playerManager);

        $game = $gameManager->startGameForPlayer($this->player1, 5);
        $game = $gameManager->addPlayerToGame($game, $this->player2, 2);
        $game->setWeatherFactor(2);
        $game = $gameManager->fightGame($game);

        $this->assertEquals(true, $game->getIsFinished());
        $this->assertEquals("A", $game->getWinner()->getName());

    }

    public function testFightWinB()
    {
        $gameManager= new GameManager($this->entityManager, $this->playerManager);
        $game = $gameManager->startGameForPlayer($this->player1, 5);
        $game = $gameManager->addPlayerToGame($game, $this->player2, 2);
        $game->setWeatherFactor(5);
        $game = $gameManager->fightGame($game);

        $this->assertEquals(true, $game->getIsFinished());
        $this->assertEquals("B", $game->getWinner()->getName());
    }
}