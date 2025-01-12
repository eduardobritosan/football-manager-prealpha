<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

#[Route('api/player')]
final class PlayerController extends AbstractController
{

    public function __construct(
        private readonly PlayerRepository  $players,
        private readonly EntityManagerInterface $objectManager,
        private readonly SerializerInterface $serializer
    ) {}

    #[Route('', methods: ['GET'])]
    public function getAll(): Response
    {

        $club = $this->players->findAll();

        return $this->json($club);
    }


    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $jsonData = $request->getContent();

        $player = $this->serializer->deserialize($jsonData, Player::class, 'json');

        if (!is_null($player->getCurrentClub())) {
            throw new BadRequestException('Unsupported behaviour: please add player without a club and then sign them up through the /api/club/signPlayer');
        }

        $this->objectManager->persist($player);
        $this->objectManager->flush();

        return $this->json($player);
    }
}
