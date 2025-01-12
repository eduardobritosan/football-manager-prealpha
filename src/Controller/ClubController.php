<?php

namespace App\Controller;

use App\Dto\BudgetUpdateDto;
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
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

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
    public function get(): Response
    {

        $club = $this->clubs->findAll();

        return $this->json($club);
    }

    /**
     * Retrieves a list of players from a specific club.
     *
     * @param int $offset The starting point for the list of players.
     * @param int $limit The maximum number of players to retrieve.
     * @param int $id The ID of the club.
     *
     * @return Response A JSON response containing the list of players.
     *
     * @Route("/{id}/players")
     */
    #[Route('/{id}/players')]
    public function getPlayers(
        #[MapQueryParameter] int $offset,
        #[MapQueryParameter] int $limit,
        int $id
    ): Response {

        $players = $this->players->getPlayersFromClub($limit, $offset, $id);

        return $this->json($players);
    }


    /**
     * Creates a new Club entity from a JSON request.
     *
     * @param Request $request The HTTP request object.
     *
     * @return Response The HTTP response object containing the created Club entity in JSON format.
     *
     * @throws BadRequestException If the content format is not JSON.
     */
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

    /**
     * Updates the club entity with the given ID.
     *
     * @Route("/{id}", methods={"PUT"})
     *
     * @param int $id The ID of the club to update.
     * @param Request $request The HTTP request object.
     *
     * @return Response The HTTP response object.
     *
     * @throws BadRequestException If the content format is not JSON.
     */
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

    /**
     * Updates the budget of a club.
     *
     * @Route("/updateBudget", methods={"POST"})
     *
     * @param Request $request The HTTP request object.
     *
     * @return Response The HTTP response object.
     *
     * @throws BadRequestException If the content format is not JSON.
     *
     * This method expects a JSON payload containing the budget update information.
     * It deserializes the JSON into a BudgetUpdateDto object, finds the club by ID,
     * and updates the club's budget if the new budget is greater than the total workforce salary.
     * If the club is not found, it returns a 404 error response.
     * If the new budget is less than the total workforce salary, it returns a 400 error response.
     * Otherwise, it updates the budget and returns the updated club entity as a JSON response.
     */
    #[Route('/updateBudget', methods: ['POST'])]
    public function updateBudget(Request $request): Response
    {
        if ('json' !== $request->getContentTypeFormat()) {
            throw new BadRequestException('Unsupported content format');
        }

        $jsonData = $request->getContent();

        $budgetUpdateDto = $this->serializer->deserialize($jsonData, BudgetUpdateDto::class, 'json');

        $entity = $this->clubs->findOneBy(["id" => $budgetUpdateDto->getClubId()]);
        if (!$entity) {
            return $this->json(["error" => "Club was not found by id:" . $budgetUpdateDto->getClubId()], 404);
        }

        if ($this->clubs->getTotalWorkforceSalary($budgetUpdateDto->getClubId()) < $budgetUpdateDto->getBudget()) {
            $entity->setBudget($budgetUpdateDto->getBudget());
            $this->objectManager->flush();
        } else {
            return $this->json(["error" => "Budget under total workforce value"], 400);
        }
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
