<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Repository\ManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

#[Route('api/manager')]
final class ManagerController extends AbstractController
{

    public function __construct(
        private readonly ManagerRepository  $managers,
        private readonly EntityManagerInterface $objectManager,
        private readonly SerializerInterface $serializer
    ) {}

    #[Route('', methods: ['GET'])]
    public function getAll(): Response
    {

        $club = $this->managers->findAll();

        return $this->json($club);
    }


    #[Route('', name: 'createManager', methods: ['POST'])]
    public function createManager(Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $jsonData = $request->getContent();

        $manager = $this->serializer->deserialize($jsonData, Manager::class, 'json');

        if (!is_null($manager->getCurrentClub())) {
            throw new BadRequestException('Unsupported behaviour: please add manager without a club and then sign them up through the /api/club/signManager');
        }

        $this->objectManager->persist($manager);
        $this->objectManager->flush();

        return $this->json($manager);
    }
}
