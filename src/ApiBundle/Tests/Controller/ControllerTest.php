<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Manager\RaceManager;
use AppBundle\Manager\RankManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllerTest extends WebTestCase
{
    protected static $user1 = [
        "motto"    => "we scare because we care",
        "name"     => "Valar Dohaeris",
        "race"     => "elf",
        "username" => "dohaeris",
        "token"    => null,
        "rank"     => null,
        "points"   => null
    ];

    protected static $user2 = [
        "motto"    => "live free or die",
        "name"     => "Valar Morghulis",
        "race"     => "dwarf",
        "username" => "morghulis",
        "token"    => null,
        "rank"     => null,
        "points"   => null
    ];

    protected static $game = [
        "id"                    => "",
        "isFinished"            => "",
        "rank"                  => "",
        "playerOne"             => "",
        'playerOneAttackCount'  => "",
        'playerOneDefenseCount' => "",
        "playerTwo"             => "",
        'playerTwoAttackCount'  => "",
        'playerTwoDefenseCount' => "",
        'winner'                => ""
    ];

    public function testIfUp()
    {
        $client = static::createClient();
        $client->request('GET', '/api/');

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

}