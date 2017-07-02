<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Race;
use AppBundle\Entity\Rank;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PlayerSerializer
{
    public function leaderBoardSerializer($players)
    {
        return $this->getPlayerSerializer($players, 'leaderboard');
    }

    public function myInfoSerializer($player)
    {
        return $this->getPlayerSerializer($player, 'myinfo');
    }

    protected function getPlayerSerializer($players, $group)
    {
        $dateCallback = function ($dateTime) {
            return $dateTime instanceof \DateTime
                ? $dateTime->format(\DateTime::RSS)
                : '';
        };

        $raceCallback = function ($race) {
            return $race instanceof Race
                ? $race->getKey()
                : "";
        };

        $rankCallback = function ($rank) {
            return $rank instanceof Rank
                ? $rank->getKey()
                : "";
        };

        $encoder = new JsonEncoder();
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer], [$encoder]);

        $normalizer->setCallbacks(
            [
                'createdAt' => $dateCallback,
                'race'      => $raceCallback,
                'rank'      => $rankCallback
            ]
        );

        $normalized_data = $serializer->normalize($players, null, ['groups' => [$group]]);

        $jsonContent = $serializer->serialize($normalized_data, 'json');

        return $jsonContent;
    }
}