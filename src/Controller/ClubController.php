<?php

namespace App\Controller;

use App\Dto\SignEmployeeDto;
use App\Entity\Club;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Repository\ClubRepository;
use App\Repository\PlayerRepository;
use App\Repository\ManagerRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('api/club')]
class ClubController extends AbstractController
{
    public function __construct(
        private readonly ClubRepository  $clubs,
        private readonly PlayerRepository  $players,
        private readonly ManagerRepository  $managers,
        private readonly EntityManagerInterface $objectManager,
        private readonly SerializerInterface $serializer
    ) {}


    #[Route('', methods: ['GET'])]
    public function getClub(): Response
    {

        $club = $this->clubs->findAll();

        return $this->json($club);
    }


    #[Route('', methods: ['POST'])]
    public function create(Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $jsonData = $request->getContent();

        $club = $this->serializer->deserialize($jsonData, Club::class, 'json');

        $this->objectManager->persist($club);
        $this->objectManager->flush();

        return $this->json($club);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $entity = $this->clubs->findOneBy(["id" => $id]);
        if (!$entity) {
            return $this->json(["error" => "Club was not found by id:" . $id], 404);
        }

        $jsonData = $request->getContent();

        $club = $this->serializer->deserialize($jsonData, Club::class, 'json');

        $entity->setName($club->getName())->setBudget($club->getBudget());

        $this->objectManager->flush();

        return $this->json($entity);
    }

    #[Route('/budget/{id}', methods: ['PUT'])]
    public function budgetUpdate(int $id, Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $entity = $this->clubs->findOneBy(["id" => $id]);
        if (!$entity) {
            return $this->json(["error" => "Club was not found by id:" . $id], 404);
        }

        $jsonData = $request->getContent();

        $club = $this->serializer->deserialize($jsonData, Club::class, 'json');

        $entity->setName($club->getName())->setBudget($club->getBudget());

        $this->objectManager->flush();

        return $this->json($entity);
    }



    #[Route('/signPlayer', methods: ['POST'])]
    public function signPlayer(Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $jsonData = $request->getContent();

        $SignEmployeeDto = $this->serializer->deserialize($jsonData, SignEmployeeDto::class, 'json');

        $player = $this->players->findOneBy(["nif" => $SignEmployeeDto->getPlayerNif()]);
        $club = $this->clubs->findOneBy(["id" => $SignEmployeeDto->getClubId()]);

        if (!$club or !$player) {
            return $this->json(["error" => "Couldn't find either club, player or both"], 404);
        }

        $result = $this->clubs->signEmployee($SignEmployeeDto, $player, $club);
        return $this->json($result);
    }


    #[Route('/releasePlayer', methods: ['POST'])]
    public function releasePlayer(Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $jsonData = $request->getContent();

        $SignEmployeeDto = $this->serializer->deserialize($jsonData, SignEmployeeDto::class, 'json');

        $player = $this->players->findOneBy(["nif" => $SignEmployeeDto->getPlayerNif()]);
        $club = $this->clubs->findOneBy(["id" => $SignEmployeeDto->getClubId()]);

        if (!$club or !$player) {
            return $this->json(["error" => "Couldn't find either club, player or both"], 404);
        }

        $result = $this->clubs->releaseEmployee($SignEmployeeDto, $player, $club);
        return $this->json($result);
    }


    #[Route('/signManager', methods: ['POST'])]
    public function signManager(Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $jsonData = $request->getContent();

        $SignEmployeeDto = $this->serializer->deserialize($jsonData, SignEmployeeDto::class, 'json');

        $manager = $this->managers->findOneBy(["nif" => $SignEmployeeDto->getPlayerNif()]);

        $club = $this->clubs->findOneBy(["id" => $SignEmployeeDto->getClubId()]);

        if (!$club or !$manager) {
            return $this->json(["error" => "Couldn't find either club, manager or both"], 404);
        }

        $result = $this->clubs->signEmployee($SignEmployeeDto, $manager, $club);
        return $this->json($result);
    }


    #[Route('/releaseManager', methods: ['POST'])]
    public function releaseManager(Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $jsonData = $request->getContent();

        $SignEmployeeDto = $this->serializer->deserialize($jsonData, SignEmployeeDto::class, 'json');

        $manager = $this->managers->findOneBy(["nif" => $SignEmployeeDto->getPlayerNif()]);
        $club = $this->clubs->findOneBy(["id" => $SignEmployeeDto->getClubId()]);

        if (!$club or !$manager) {
            return $this->json(["error" => "Couldn't find either club, manager or both"], 404);
        }

        $result = $this->clubs->releaseEmployee($SignEmployeeDto, $manager, $club);
        return $this->json($result);
    }
}
