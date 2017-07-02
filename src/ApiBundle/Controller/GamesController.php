<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Manager\PlayerManager;
use AppBundle\Serializer\GameSerializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Manager\GameManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class GamesController extends JsonController
{
    /**
     * @Route("/games", name="start_new_game")
     * @Method("POST")
     */
    public function startNewGameAction(Request $request)
    {
        /**
         * @var Player $player
         * @var PlayerManager $playerManager
         * @var GameManager $gameManager
         * @var GameSerializer $gameSerializer
         */
        $username = $this->get('security.context')->getToken()->getUser();
        $data = json_decode($request->getContent());
        if (!isset($data->attacks)) {
            return $this->jsonErrResponse("Please specify 'attacks' in your request body");
        }
        $attacks = $data->attacks;

        $playerManager = $this->get("app_player_manager");
        $player = $playerManager->getPlayerByUsername($username);

        if ($data->attacks > $player->getRank()->getMoves()) {
            return $this->jsonErrResponse("Number of attacks shouldn't be more than " . $player->getRank()->getMoves());
        }

        $gameManager = $this->get("app_game_manager");
        $game = $gameManager->startGameForPlayer($player, $attacks);

        $gameSerializer = $this->get("app_game_serializer");
        $jsonContent = $gameSerializer->getDefault($game);

        return $this->jsonResponse($jsonContent, Response::HTTP_CREATED);
    }

    /**
     * @Route("/games/available", name="available_games_by_rank")
     * @Method("GET")
     */
    public function getAvailableGamesAction()
    {
        /**
         * @var GameManager $gameManager
         * @var GameSerializer $gameSerializer
         * @var PlayerManager $playerManager
         * @var Player $player
         */

        $username = $this->get('security.context')->getToken()->getUser();
        $playerManager = $this->get("app_player_manager");
        $player = $playerManager->getPlayerByUsername($username);

        $gameManager = $this->get('app_game_manager');
        $gameSerializer = $this->get('app_game_serializer');
        $games = $gameManager->getAvailableGames($player, $player->getRank());

        $jsonContent = $gameSerializer->getDefault($games);

        return $this->jsonResponse($jsonContent);
    }

    /**
     * @Route("/games/{id}/join", name="join_to_game")
     * @Method("POST")
     */
    public function addPlayerTwoToGameAction($id, Request $request)
    {
        /**
         * @var Player $player
         * @var PlayerManager $playerManager
         * @var GameManager $gameManager
         * @var GameSerializer $gameSerializer
         * @var Game $game
         */
        $data = json_decode($request->getContent());
        if (!isset($data->attacks)) {
            return $this->jsonErrResponse("Please specify 'attacks' in your request body");
        }
        $attacks = $data->attacks;

        $username = $this->get('security.context')->getToken()->getUser();
        $playerManager = $this->get("app_player_manager");
        $player = $playerManager->getPlayerByUsername($username);

        if ($data->attacks > $player->getRank()->getMoves()) {
            return $this->jsonErrResponse("Number of attacks shouldn't be more than " . $player->getRank()->getMoves());
        }

        $gameManager = $this->get("app_game_manager");
        $game = $gameManager->getGameForPlayerByID($id, $player);
        if (is_null($game)) {
            return $this->jsonErrResponse("Game with such id is not found");
        }

        $game = $gameManager->addPlayerToGame($game, $player, $attacks);

        $gameSerializer = $this->get("app_game_serializer");
        $jsonContent = $gameSerializer->getDefault($game);

        return $this->jsonResponse($jsonContent);
    }
    /**
     * @Route("/games/{id}/fight", name="fight_game")
     * @Method("POST")
     */
    public function fightGameAction($id)
    {
        /**
         * @var Game $game
         * @var GameManager $gameManager
         * @var GameSerializer $gameSerializer
         */
        $gameManager = $this->get("app_game_manager");
        $game = $gameManager->getGameById($id);
        if (is_null($game)) {
            return $this->jsonErrResponse("Game with such id is not found");
        }
        try {
            $game = $gameManager->fightGame($game);
        } catch (\InvalidArgumentException $e) {
            return $this->jsonErrResponse($e->getMessage());
        }

        $gameSerializer = $this->get("app_game_serializer");
        $jsonContent = $gameSerializer->getFinished($game);

        return $this->jsonResponse($jsonContent);
    }
}
