<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Player;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PlayerManager
{
    protected $em;

    /**
     * GameManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $token
     */
    public function getPlayerByToken($token)
    {
        $player = $this->em->getRepository("AppBundle:Player")->findOneByToken($token);

        if (is_null($player)) {
            throw new NotFoundHttpException("There is no user with this token: " . $token);
        }

        return $player;
    }

    public function getPlayerByUsername($username)
    {
        $player = $this->em->getRepository("AppBundle:Player")->findOneByUsername($username);

        if (is_null($player)) {
            throw new NotFoundHttpException("There is no user with this username: " . $username);
        }

        return $player;
    }

    /**
     * @param string $rank
     * @param integer $page
     */
    public function getLeaderboard($rank, $page)
    {
        $itemsOnPage = 10;
        $players = $this->em->getRepository("AppBundle:Player")->findBy(['rank' => $rank], ['points' => 'DESC'], $itemsOnPage , $itemsOnPage * $page);

        return $players;
    }

    /**
     * @param array $params
     * @return Player
     */
    public function createPlayer($params)
    {
        if (!isset($params['username'])
        or !isset($params['name'])
            or !isset($params['race'])) {
            throw new \InvalidArgumentException("Check your arguments: name, username, race are required.");
        }
        if (!in_array($params['race'], RaceManager::getAvailableRaces())) {
            throw new \InvalidArgumentException("Available races are: " . implode(', ', RaceManager::getAvailableRaces()));
        }

        $player = new Player();
        $player->setUsername($params['username']);
        $player->setToken(base64_encode(random_bytes(10)));
        $player->setName($params['name']);
        $player->setMotto($params['motto']);
        $player->setRace(strtolower($params['race']));
        $player->setRank(RankManager::getInitialRank());
        $player->setPoints(0);

        try {
            $this->em->persist($player);
            $this->em->flush();
            return $player;
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'SQLSTATE[23000]')) {
                throw new \InvalidArgumentException("Username already taken, try another one");
            } else {
                throw new \InvalidArgumentException($e->getMessage());
            }
        }
    }


    /**
     * @param Player $player
     * @param integer $newPoints
     * @return mixed
     */
    public function registerWin(Player $player, $newPoints)
    {
        $player->setPoints($player->getPoints() + $newPoints);

        $newRank = RankManager::getRankByPoints($player->getPoints());

        if (!is_null($newRank) and $newRank->getKey() != $player->getRank()->getKey()) {
            $player->setRank($newRank->getKey());
        }
        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }
}