<?php

namespace ApiBundle\Tests\Controller;

use AppBundle\Manager\RankManager;
use Symfony\Component\HttpFoundation\Response;

class GameControllerTest extends ControllerTest
{
    public function testCreateGameTooMuchAttacks()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user1['token']
            ],
            json_encode(['attacks' => 10])
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testCreateGame()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user1['token']
            ],
            json_encode(['attacks' => 3])
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertArraySubset(
            ['rank' => ['key' => self::$user1['rank'], 'moves' => 5], 'playerOne' => self::$user1['name'], 'isFinished' => false],
            json_decode($client->getResponse()->getContent(), true)
        );

        self::$game = array_merge(self::$game, json_decode($client->getResponse()->getContent(), true));
    }

    public function testGetAvailableGamesForUser1()
    {
        $client = static::createClient();
        $client->request('GET', '/api/games/available', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user1['token']
            ]
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            [],
            json_decode($client->getResponse()->getContent(), true)
        );
    }
    public function testGetAvailableGamesForUser2()
    {
        $client = static::createClient();
        $client->request('GET', '/api/games/available', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user2['token']
            ]
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertArraySubset(
            json_decode($client->getResponse()->getContent(), true),
            [self::$game]
        );
    }

    public function testAddUserToOwnGame()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games/'.self::$game['id'].'/join', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user1['token']
            ],
            json_encode(['attacks' => 4])
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testAddUserToGame()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games/'.self::$game['id'].'/join', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user2['token']
            ],
            json_encode(['attacks' => 4])
        );

        self::$game['playerTwo'] = self::$user2['name'];

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertArraySubset(
            json_decode($client->getResponse()->getContent(), true),
            self::$game
        );
    }


    public function testAddUserToGameTooMuchAttacks()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games/'.self::$game['id'].'/join', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user2['token']
            ],
            json_encode(['attacks' => 15])
        );

        self::$game['playerTwo'] = self::$user2['name'];

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testAddUserToGameWrongID()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games/9879879/join', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user2['token']
            ],
            json_encode(['attacks' => 4])
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }


    public function testAddUserToGameForgotAttacks()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games/9879879/join', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user2['token']
            ]
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testFightWrongGameID()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games/9879879/fight', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user2['token']
            ]
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testFight()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games/'.self::$game['id'].'/fight', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user2['token']
            ]
        );

        self::$game['isFinished'] = true;
        self::$game['playerOne'] = self::$user1['name'];
        self::$game['playerOneAttackCount'] = 3;
        self::$game['playerOneDefenseCount'] = 2;
        self::$game['playerTwo'] = self::$user2['name'];
        self::$game['playerTwoAttackCount'] = 4;
        self::$game['playerTwoDefenseCount'] = 1;
        $data = json_decode($client->getResponse()->getContent(), true);
        self::$game['winner'] = $data['winner'];

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertArraySubset(
            self::$game,
            $data
        );
    }

    public function testFightAgain()
    {
        $client = static::createClient();
        $client->request('POST', '/api/games/'.self::$game['id'].'/fight', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user2['token']
            ]
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testLeaderboardAfterFight()
    {
        $client = static::createClient();
        $client->request('GET', '/api/leaderboard/' . RankManager::getInitialRank());

        $result = [self::$user1, self::$user2];

        if (self::$game['winner'] == self::$user2['name']) {
            self::$user2['points'] = 1;
            $result = [self::$user2, self::$user1];
        } elseif (self::$game['winner'] == self::$user1['name']) {
            self::$user1['points'] = 3;
            $result = [self::$user1, self::$user2];
        }

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertArraySubset(
            json_decode($client->getResponse()->getContent(), true),
            $result
        );
    }
}