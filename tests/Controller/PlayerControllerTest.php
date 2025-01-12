<?php

namespace App\Tests\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PlayerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/player/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Player::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Player index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'player[id]' => 'Testing',
            'player[nif]' => 'Testing',
            'player[name]' => 'Testing',
            'player[salary]' => 'Testing',
            'player[releaseClause]' => 'Testing',
            'player[currentClub]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Player();
        $fixture->setId('My Title');
        $fixture->setNif('My Title');
        $fixture->setName('My Title');
        $fixture->setSalary('My Title');
        $fixture->setReleaseClause('My Title');
        $fixture->setCurrentClub('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Player');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Player();
        $fixture->setId('Value');
        $fixture->setNif('Value');
        $fixture->setName('Value');
        $fixture->setSalary('Value');
        $fixture->setReleaseClause('Value');
        $fixture->setCurrentClub('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'player[id]' => 'Something New',
            'player[nif]' => 'Something New',
            'player[name]' => 'Something New',
            'player[salary]' => 'Something New',
            'player[releaseClause]' => 'Something New',
            'player[currentClub]' => 'Something New',
        ]);

        self::assertResponseRedirects('/player/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getId());
        self::assertSame('Something New', $fixture[0]->getNif());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getSalary());
        self::assertSame('Something New', $fixture[0]->getReleaseClause());
        self::assertSame('Something New', $fixture[0]->getCurrentClub());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Player();
        $fixture->setId('Value');
        $fixture->setNif('Value');
        $fixture->setName('Value');
        $fixture->setSalary('Value');
        $fixture->setReleaseClause('Value');
        $fixture->setCurrentClub('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/player/');
        self::assertSame(0, $this->repository->count([]));
    }
}
