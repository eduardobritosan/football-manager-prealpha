<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * @Route("api/player")
 * 
 * Controller for handling player-related API requests.
 */
#[Route('api/player')]
final class PlayerController extends AbstractController
{
    /**
     * Constructor for PlayerController.
     *
     * @param PlayerRepository $players Repository for player entities.
     * @param EntityManagerInterface $objectManager Entity manager for handling database operations.
     * @param SerializerInterface $serializer Serializer for handling JSON data.
     */
    public function __construct(
        private readonly PlayerRepository  $players,
        private readonly EntityManagerInterface $objectManager,
        private readonly SerializerInterface $serializer
    ) {}

    /**
     * Get all players.
     *
     * @Route("", methods={"GET"})
     * 
     * @return Response JSON response containing all players.
     */
    #[Route('', methods: ['GET'])]
    public function getAll(): Response
    {
        $players = $this->players->findAll();
        return $this->json($players);
    }

    /**
     * Create a new player.
     *
     * @Route("", name="create", methods={"POST"})
     * 
     * @param Request $request HTTP request containing player data in JSON format.
     * 
     * @return Response JSON response containing the created player.
     * 
     * @throws BadRequestException If the content format is not JSON or if the player already has a club.
     */
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
