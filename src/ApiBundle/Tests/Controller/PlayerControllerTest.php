<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class PlayerControllerTest extends ControllerTest
{
    public function testGetMyInfo()
    {
        $client = static::createClient();
        $client->request('GET', '/api/player', [], [],
            [
                'Content-Type' => 'application/json',
                'HTTP_token'   => self::$user1['token']
            ]
        );

        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertArraySubset(
            self::$user1,
            json_decode($client->getResponse()->getContent(), true)
        );

    }
}