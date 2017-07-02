<?php

namespace ApiBundle\Controller;

use AppBundle\Manager\PlayerManager;
use AppBundle\Manager\RaceManager;
use AppBundle\Manager\RankManager;
use AppBundle\Serializer\PlayerSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends JsonController
{
    /**
     * @Route("/", name="api")
     * @Method("GET")
     */
    public function getHomeAction()
    {
        $jsonContent = "";
        return $this->jsonResponse($jsonContent);
    }

    /**
     * @Route("/races", name="races")
     * @Method("GET")
     */
    public function getRacesAction()
    {
        $races = RaceManager::getAvailableRaces();


        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $jsonContent = $serializer->serialize($races, 'json');

        return $this->jsonResponse($jsonContent);
    }

    /**
     * @Route("/leaderboard", name="leaderboard")
     * @Route("/leaderboard/{rank}", name="leaderboard_rank")
     * @Route("/leaderboard/{rank}/{page}", name="leaderboard_rank_page")
     * @Method("GET")
     */
    public function getLeaderboardAction($rank = 'soldier', $page = 0)
    {
        if (!in_array($rank, RankManager::getAvailableRanks())) {
            return $this->jsonErrResponse('rank is not available');
        }

        /**
         * @var PlayerManager $playerManager
         * @var PlayerSerializer $playerSerializer
         */
        $playerManager = $this->get("app_player_manager");
        $playerSerializer = $this->get("app_player_serializer");

        $leaderboard = $playerManager->getLeaderboard(strtolower($rank), $page);

        $jsonContent = $playerSerializer->leaderBoardSerializer($leaderboard);

        return $this->jsonResponse($jsonContent);
    }


    /**
     * @Route("/register", name="register")
     * @Method("POST")
     */
    public function registerAction(Request $request)
    {
        try {
            /**
             * @var PlayerManager $playerManager
             * @var PlayerSerializer $playerSerializer
             */
            $playerManager = $this->get('app_player_manager');
            $player = $playerManager->createPlayer(json_decode($request->getContent(), true));
            $playerSerializer = $this->get("app_player_serializer");

            $jsonContent = $playerSerializer->myInfoSerializer($player);
            $httpCode = Response::HTTP_CREATED;

            return $this->jsonResponse($jsonContent, $httpCode);

        } catch (\InvalidArgumentException $e) {
            return $this->jsonErrResponse($e->getMessage());
        }
    }
}
