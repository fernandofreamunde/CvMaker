<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Exception;
use Faker\Factory;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiFlowTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testContactsCanBeListed(): void
    {
        $response = static::createClient()->request(Request::METHOD_GET, '/api/contacts');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/contacts']);
    }

    public function testCanCreateContactAndRead(): void
    {
        $client = static::createClient();
        $faker = Factory::create();
        $email = $faker->email();
        $response = $client->request(Request::METHOD_POST, '/api/contacts', [
            'json' => [
                "name" => "email",
                "content" => $email,
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["content" => $email]);

        $response = $client->request(Request::METHOD_GET, '/api/contacts');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
    }

    public function testCannotCreateContactWithBadData(): void
    {
        $client = static::createClient();
        $faker = Factory::create();
        $email = $faker->email();
        $response = $client->request(Request::METHOD_POST, '/api/contacts', [
            'json' => [
                "name" => 1,
                "content" => 2,
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testCanCreateContactAndUpdateIt(): void
    {
        $client = static::createClient();
        $faker = Factory::create();
        $initialEmail = $faker->email();
        $response = $client->request(Request::METHOD_POST, '/api/contacts', [
            'json' => [
                "name" => "email",
                "content" => $initialEmail,
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["content" => $initialEmail]);

        $secondEmail = $faker->email();
        $response = $client->request(Request::METHOD_PUT, $response->toArray()['@id'], [
            'json' => [
                "name" => "email",
                "content" => $secondEmail,
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["content" => $secondEmail]);

        $response = $client->request(Request::METHOD_GET, '/api/contacts');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
        $this->assertSame('email', $response->toArray()['hydra:member'][0]['name']);
        $this->assertSame($secondEmail, $response->toArray()['hydra:member'][0]['content']);
    }

    public function testCanCreateContactAndDeleteIt(): void
    {
        $client = static::createClient();
        $faker = Factory::create();
        $email = $faker->email();
        $response = $client->request(Request::METHOD_POST, '/api/contacts', [
            'json' => [
                "name" => "email",
                "content" => $email,
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["content" => $email]);

        $response = $client->request(Request::METHOD_DELETE, $response->toArray()['@id']);

        $this->assertResponseIsSuccessful();

        $response = $client->request(Request::METHOD_GET, '/api/contacts');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 0]);
    }

    public function testCannotUsePatchRequest(): void
    {
        $client = static::createClient();
        $faker = Factory::create();
        $email = $faker->email();
        $response = $client->request(Request::METHOD_POST, '/api/contacts', [
            'json' => [
                "name" => "email",
                "content" => $email,
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["content" => $email]);

        $secondEmail = $faker->email();
        $response = $client->request(Request::METHOD_PATCH, $response->toArray()['@id'], [
            'json' => [
                "content" => $secondEmail,
            ]
        ]);

        $this->assertResponseStatusCodeSame(405);
    }

    public function testEducationCanBeListed(): void
    {
        $response = static::createClient()->request(Request::METHOD_GET, '/api/education');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/education']);
    }

    /**
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function testCanCreateEducationAndReadIt(): void
    {
        $client = static::createClient();
        $faker = Factory::create();

        $response = $client->request(Request::METHOD_POST, '/api/education', [
            'json' => [
                "schoolName" => 10,
                "degreeName" => 1,
                "graduationYear" => 1,
            ]
        ]);

        // Fail bad data
        $this->assertResponseStatusCodeSame(400);

        $schoolName = $this->getRandomSchoolName();
        $degreeName = $this->getRandomDegreeName();
        $graduationYear = $faker->numberBetween(1970, 2022);
        $response = $client->request(Request::METHOD_POST, '/api/education', [
            'json' => [
                "schoolName" => $schoolName,
                "degreeName" => $degreeName,
                "graduationYear" => "$graduationYear",
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["schoolName" => $schoolName]);
        $this->assertJsonContains(["degreeName" => $degreeName,]);
        $this->assertJsonContains(["graduationYear" => "$graduationYear"]);

        $response = $client->request(Request::METHOD_GET, '/api/education');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
    }

    /**
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function testCanCreateEducationAndUpdateIt(): void
    {
        $client = static::createClient();
        $faker = Factory::create();

        $schoolName = $this->getRandomSchoolName();
        $degreeName = $this->getRandomDegreeName();
        $graduationYear = $faker->numberBetween(1970, 2022);
        $response = $client->request(Request::METHOD_POST, '/api/education', [
            'json' => [
                "schoolName" => $schoolName,
                "degreeName" => $degreeName,
                "graduationYear" => "$graduationYear",
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["schoolName" => $schoolName]);
        $this->assertJsonContains(["degreeName" => $degreeName,]);
        $this->assertJsonContains(["graduationYear" => "$graduationYear"]);

        $schoolNameUpdate = $this->getRandomSchoolName();
        $degreeNameUpdate = $this->getRandomDegreeName();
        $graduationYearUpdate = $faker->numberBetween(1970, 2022);

        // update the entity
        $response = $client->request(Request::METHOD_PUT, $response->toArray()['@id'], [
            'json' => [
                "schoolName" => $schoolNameUpdate,
                "degreeName" => $degreeNameUpdate,
                "graduationYear" => "$graduationYearUpdate",
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["schoolName" => $schoolNameUpdate]);
        $this->assertJsonContains(["degreeName" => $degreeNameUpdate]);
        $this->assertJsonContains(["graduationYear" => "$graduationYearUpdate"]);

        $response = $client->request(Request::METHOD_GET, '/api/education');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);

        $response = $response->toArray();
        $this->assertSame($schoolNameUpdate, $response['hydra:member'][0]['schoolName']);
        $this->assertSame($degreeNameUpdate, $response['hydra:member'][0]['degreeName']);
        $this->assertSame("$graduationYearUpdate", $response['hydra:member'][0]['graduationYear']);
    }

    public function testCanCreateEducationAndDeleteIt(): void
    {
        $client = static::createClient();
        $faker = Factory::create();
        $graduationYear = $faker->numberBetween(1970, 2022);

        $response = $client->request(Request::METHOD_POST, '/api/education', [
            'json' => [
                "schoolName" => $this->getRandomSchoolName(),
                "degreeName" => $this->getRandomDegreeName(),
                "graduationYear" => "$graduationYear",
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $response = $client->request(Request::METHOD_DELETE, $response->toArray()['@id']);

        $this->assertResponseIsSuccessful();

        $response = $client->request(Request::METHOD_GET, '/api/education');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 0]);
    }

    public function testCannotUsePatchRequest(): void
    {
        $client = static::createClient();
        $faker = Factory::create();
        $graduationYear = $faker->numberBetween(1970, 2022);

        $response = $client->request(Request::METHOD_POST, '/api/education', [
            'json' => [
                "schoolName" => $this->getRandomSchoolName(),
                "degreeName" => $this->getRandomDegreeName(),
                "graduationYear" => "$graduationYear",
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $secondEmail = $faker->email();
        $response = $client->request(Request::METHOD_PATCH, $response->toArray()['@id'], [
            'json' => [
                "content" => $secondEmail,
            ]
        ]);

        $this->assertResponseStatusCodeSame(405);
    }

    /**
     * @throws Exception
     */
    private function getRandomSchoolName()
    {
        $fakeSchoolNames = [
            'Codetube University of India',
            'Academy of the Code',
            'Portuguese School of Trial and Error',
            'Copy and Paste Academy of Ideas',
        ];

        return $this->getRandomItem($fakeSchoolNames);
    }

    /**
     * @throws Exception
     */
    private function getRandomDegreeName()
    {
        // some funny names for the kind of degree
        $fakeDegrees = ['Doctorate', 'Bachelor', 'Master', 'Autodidactus', 'StackOverflous', 'Tutorialus'];
        $fakeSubjects = ['Frontend', 'Backend', 'Fullstack', 'Rest Api', 'GraphQL'];

        return $this->getRandomItem($fakeDegrees) . ' in ' . $this->getRandomItem($fakeSubjects);
    }

    /**
     * @throws Exception
     */
    private function getRandomItem(array $array)
    {
        return $array[random_int(0, count($array) - 1)];
    }
}
