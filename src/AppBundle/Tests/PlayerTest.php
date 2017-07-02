<?php

namespace AppBundle\Manager\Tests;

use AppBundle\Manager\PlayerManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class ProductRepositoryTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testPlayerCreation()
    {
        $playerManager = static::$kernel->getContainer()->get('app_player_manager');
        $params = [
            "username" => "john",
            "name"     => "John",
            "motto"    => "Live Free Or Die",
            "race"     => "elf",
        ];
        $player = $playerManager->createPlayer($params);

        $this->assertNotNull($player);
    }

    public function testDuplicatePlayerCreation()
    {
        $playerManager = static::$kernel->getContainer()->get('app_player_manager');
        $params = [
            "username" => "john",
            "name"     => "John",
            "motto"    => "Live Free Or Die",
            "race"     => "elf",
        ];
        try {
            $player = $playerManager->createPlayer($params);
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Username already taken, try another one', $e->getMessage());
        }
    }

    public function testRegisterWin()
    {
        /**
         * @var PlayerManager $playerManager
         */
        $playerManager = static::$kernel->getContainer()->get('app_player_manager');
        $player = $playerManager->getPlayerByUsername('john');

        $this->assertEquals(0, $player->getPoints());
        $this->assertEquals("soldier", $player->getRank()->getKey());

        $newPlayer = $playerManager->registerWin($player, 13);

        $this->assertEquals(13, $newPlayer->getPoints());
        $this->assertEquals("sergeant", $newPlayer->getRank()->getKey());
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}