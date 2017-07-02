<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Manager\RaceManager;
use AppBundle\Manager\RankManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends ControllerTest
{
    public function testGetRaces()
    {
        $client = static::createClient();
        $client->request('GET', '/api/races');

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertArraySubset(
            RaceManager::getAvailableRaces(),
            json_decode($client->getResponse()->getContent(), true)
        );
    }

    public function testRegisterPlayer1()
    {
        $client = static::createClient();
        $client->request('POST', '/api/register', [], [], ['Content-Type' => 'application/json'],
            json_encode(self::$user1)
        );
        $userData = json_decode($client->getResponse()->getContent());

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(0, $userData->points);
        $this->assertEquals(RankManager::getInitialRank(), $userData->rank);

        self::$user1['rank'] = $userData->rank;
        self::$user1['points'] = $userData->points;
        self::$user1['token'] = $userData->token;
    }

    public function testRegisterPlayer2()
    {
        $client = static::createClient();
        $client->request('POST', '/api/register', [], [], ['Content-Type' => 'application/json'],
            json_encode(self::$user2)
        );

        $userData = json_decode($client->getResponse()->getContent());

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(0, $userData->points);
        $this->assertEquals(RankManager::getInitialRank(), $userData->rank);

        self::$user2['rank'] = $userData->rank;
        self::$user2['points'] = $userData->points;
        self::$user2['token'] = $userData->token;
    }

    public function testRegisterDuplicate()
    {
        $client = static::createClient();
        $client->request('POST', '/api/register', [], [], ['Content-Type' => 'application/json'],
            json_encode(self::$user1)
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertFalse($client->getResponse()->isSuccessful());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testRegister405()
    {
        $client = static::createClient();
        $client->request('GET', '/api/register', [], [], ['Content-Type' => 'application/json'],
            json_encode(self::$user1)
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    public function testLeaderboard()
    {
        $client = static::createClient();
        $client->request('GET', '/api/leaderboard');

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertArraySubset(
            json_decode($client->getResponse()->getContent(), true),
            [self::$user1, self::$user2]
        );
    }

    public function testLeaderboardFail()
    {
        $client = static::createClient();
        $client->request('GET', '/api/leaderboard/unkownrank' . RankManager::getInitialRank());

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

}
