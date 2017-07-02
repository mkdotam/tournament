<?php

namespace ApiBundle\Controller;

use AppBundle\Manager\PlayerManager;
use AppBundle\Serializer\PlayerSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PlayerController extends JsonController
{
    /**
     * @Route("/player", name="player_myinfo")
     * @Method("GET")
     */
    public function myInfoAction()
    {
        /**
         * @var PlayerManager $playerManager
         * @var PlayerSerializer $playerSerializer
         */
        $username = $this->get('security.context')->getToken()->getUser();

        $playerManager = $this->get("app_player_manager");
        $playerSerializer = $this->get("app_player_serializer");
        $player = $playerManager->getPlayerByUsername($username);

        $jsonContent = $playerSerializer->myInfoSerializer($player);

        return $this->jsonResponse($jsonContent);
    }
}
