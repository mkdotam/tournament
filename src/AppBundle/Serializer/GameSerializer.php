<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Player;
use AppBundle\Entity\Rank;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class GameSerializer
{
    public function getFinished($games)
    {
        return $this->getGameSerializer($games, "finished");
    }

    public function getDefault($games)
    {
        return $this->getGameSerializer($games, "default");
    }

    protected function getGameSerializer($games, $group)
    {
        $dateCallback = function ($dateTime) {
            return $dateTime instanceof \DateTime
                ? $dateTime->format(\DateTime::RSS)
                : '';
        };

        $playerCallback = function ($player) {
            return $player instanceof Player
                ? $player->__toString()
                : "";
        };

        $rankCallback = function ($rank) {
            return $rank instanceof Rank
                ? ['key' => $rank->getKey(), 'moves' => $rank->getMoves()]
                : "";
        };


        $encoder = new JsonEncoder();
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer], [$encoder]);

        $normalizer->setIgnoredAttributes(['weatherFactor']);

        $normalizer->setCallbacks(
            [
                'createdAt' => $dateCallback,
                'playerOne' => $playerCallback,
                'playerTwo' => $playerCallback,
                'rank'      => $rankCallback,
                'winner'    => $playerCallback
            ]
        );

        $normalized_data = $serializer->normalize($games, null, ['groups' => [$group]]);

        $jsonContent = $serializer->serialize($normalized_data, 'json');

        return $jsonContent;
    }

}