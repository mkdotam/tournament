<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use Doctrine\ORM\EntityManager;

class GameManager
{
    protected $em;

    /**
     * GameManager constructor.
     * @param EntityManager $entityManager
     * @param PlayerManager $playerManager
     */
    public function __construct(EntityManager $entityManager, PlayerManager $playerManager)
    {
        $this->em = $entityManager;
        $this->playerManager = $playerManager;
    }

    public function getAvailableGames(Player $player, $rank)
    {
        if (!in_array($rank, RankManager::getAvailableRanks())) {
            throw new \InvalidArgumentException("Wrong rank passed down");
        }

        return $this->em->getRepository('AppBundle:Game')->getAvailableGames($player, $rank);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function getGameById($id)
    {
        return $this->em->getRepository('AppBundle:Game')->find($id);
    }

    public function getGameForPlayerByID($id, Player $player)
    {
        return $this->em->getRepository('AppBundle:Game')->findAGameToJoin($id, $player);
    }

    /**
     * @param Player $player
     * @param integer $attacks
     * @return Game
     */
    public function startGameForPlayer(Player $player, $attacks)
    {
        $game = new Game();
        $game->setRank($player->getRank()->getKey());
        $game->setPlayerOne($player);
        $game->setPlayerOneAttackCount($attacks);
        $game->setPlayerOneDefenseCount($player->getMoves() - $attacks);

        $this->em->persist($game);
        $this->em->flush();

        return $game;
    }

    /**
     * @param Game $game
     * @param Player $player
     * @param integer $attacks
     * @return Game
     */
    public function addPlayerToGame(Game $game, Player $player, $attacks)
    {
        $game->setPlayerTwo($player);
        $game->setPlayerTwoAttackCount($attacks);
        $game->setPlayerTwoDefenseCount($player->getMoves() - $attacks);

        $this->em->persist($game);
        $this->em->flush();

        return $game;
    }

    /**
     * @param Game $game
     * @return Game
     */
    public function initWeather(Game $game)
    {
        $weatherFactor = $this->calculateWeatherCondition();
        $game->setWeatherFactor($weatherFactor);

        $this->em->persist($game);
        $this->em->flush();

        return $game;
    }

    /**
     * @param Game $game
     * @return Game
     */
    public function fightGame(Game $game)
    {
        if ($game->getIsFinished()) {
            throw new \InvalidArgumentException("The game you've chosen already finished.");
        }
        if (is_null($game->getWeatherFactor())) {
            $this->initWeather($game);
        }

        $winner = $this->proccessWinner($game);
        $game->setWinner($winner);
        $game->setIsFinished(true);

        $this->em->persist($game);
        $this->em->flush();

        return $game;
    }


    /**
     * @return int
     */
    private function calculateWeatherCondition()
    {
        return rand(1, 9);
    }

    /**
     * @param Game $game
     * @return Player|null
     */
    private function proccessWinner(Game $game)
    {
        $playerOnePoints = $this->getPoints(
            $game->getPlayerOne()->getAttackPower(),
            $game->getPlayerOneAttackCount(),
            $game->getPlayerTwo()->getDefensePower(),
            $game->getPlayerTwoDefenseCount(),
            $game->getWeatherFactor()
        );

        $playerTwoPoints = $this->getPoints(
            $game->getPlayerTwo()->getAttackPower(),
            $game->getPlayerTwoAttackCount(),
            $game->getPlayerOne()->getDefensePower(),
            $game->getPlayerOneDefenseCount(),
            $game->getWeatherFactor()
        );

        if ($playerOnePoints > $playerTwoPoints) {
            $winner = $game->getPlayerOne();
            $loser = $game->getPlayerTwo();
        } else {
            if ($playerOnePoints < $playerTwoPoints) {
                $winner = $game->getPlayerTwo();
                $loser = $game->getPlayerOne();
            } else {
                $loser = null;
                $winner = null;
            }
        }

        if (!is_null($winner)) {
            $newPoints = $this->getWinnersPoints($winner, $loser);

            $this->playerManager->registerWin($winner, $newPoints);
        }

        return $winner;
    }

    /**
     * @param Player $winner
     * @param Player $loser
     * @return integer
     */
    private function getWinnersPoints(Player $winner, Player $loser)
    {
        $points = 0;

        if ($winner->getAttackPower() > $loser->getAttackPower()) {
            $points = 1;
        } elseif ($winner->getAttackPower() == $loser->getAttackPower()) {
            $points = 2;
        } elseif ($winner->getAttackPower() < $loser->getAttackPower()) {
            $points = 3;
        }

        return $points;
    }

    /**
     * @param integer $attackCoef
     * @param integer $attackCount
     * @param integer $defenceCoef
     * @param integer $defenseCount
     * @param integer $weatherFactor
     * @return integer
     */
    private function getPoints($attackCoef, $attackCount, $defenceCoef, $defenseCount, $weatherFactor)
    {
        $realAttackCoef = $attackCoef - $weatherFactor;
        $points =
            (($attackCount - $defenseCount) * $realAttackCoef) // number of full power attacks
            + $defenseCount * ($realAttackCoef - $defenceCoef); // number of blocked attacks

        return $points;
    }
}