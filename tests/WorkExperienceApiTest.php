<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\helper\HelperTrait;
use Carbon\Carbon;
use Faker\Factory;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;

class WorkExperienceApiTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use HelperTrait;

    public function testExperienceCanBeListed(): void
    {
        $response = static::createClient()->request(Request::METHOD_GET, '/api/work_experiences');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/work_experiences']);
    }

    public function testCanCreateExperienceAndReadIt(): void
    {
        $client = static::createClient();
        $faker = Factory::create();

        $company = $faker->company();
        $role = $this->getRandomRole();
        $startingDate = $faker->dateTimeInInterval('-30 years', 'now')->format('Y-m-d');
        $endingDate = $faker->boolean() ? null : $faker->dateTimeInInterval($startingDate, 'now')->format('Y-m-d');
        $description = $faker->text();

        $response = $client->request(Request::METHOD_POST, '/api/work_experiences', [
            'json' => [
                "companyName" => $company,
                "role" => $role,
                "startingDate" => $startingDate,
                "endingDate" => $endingDate,
                "description" => $description,
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["companyName" => $company]);
        $this->assertJsonContains(["role" => $role]);
        $this->assertJsonContains(["startingDate" => $startingDate.'T00:00:00+00:00']);
        $this->assertJsonContains(["endingDate" => !is_null($endingDate) ? $endingDate.'T00:00:00+00:00' : null]);
        $this->assertJsonContains(["description" => $description]);

        $response = $client->request(Request::METHOD_GET, '/api/work_experiences');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
    }

    public function testCanCreateExperienceAndUpdateIt(): void
    {
        $client = static::createClient();
        $faker = Factory::create();

        $company = $faker->company();
        $role = $this->getRandomRole();
        $startingDate = $faker->dateTimeInInterval('-30 years', 'now')->format('Y-m-d');
        $endingDate = $faker->boolean() ? null : $faker->dateTimeInInterval($startingDate, 'now')->format('Y-m-d');
        $description = $faker->text();

        $response = $client->request(Request::METHOD_POST, '/api/work_experiences', [
            'json' => [
                "companyName" => $company,
                "role" => $role,
                "startingDate" => $startingDate,
                "endingDate" => $endingDate,
                "description" => $description,
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["companyName" => $company]);
        $this->assertJsonContains(["role" => $role]);
        $this->assertJsonContains(["startingDate" => $startingDate.'T00:00:00+00:00']);
        $this->assertJsonContains(["endingDate" => !is_null($endingDate) ? $endingDate.'T00:00:00+00:00' : null]);
        $this->assertJsonContains(["description" => $description]);

        $companySecond = $faker->company();
        $roleSecond = $this->getRandomRole();
        $startingDateSecond = $faker->dateTimeInInterval('-30 years', 'now')->format('Y-m-d');
        $endingDateSecond = $faker->boolean() ? null : $faker->dateTimeInInterval($startingDate, 'now')->format('Y-m-d');
        $descriptionSecond = $faker->text();

        // update the entity
        $response = $client->request(Request::METHOD_PUT, $response->toArray()['@id'], [
            'json' => [
                "companyName" => $companySecond,
                "role" => $roleSecond,
                "startingDate" => $startingDateSecond,
                "endingDate" => $endingDateSecond,
                "description" => $descriptionSecond,
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["companyName" => $companySecond]);
        $this->assertJsonContains(["role" => $roleSecond]);
        $this->assertJsonContains(["startingDate" => $startingDateSecond.'T00:00:00+00:00']);
        $this->assertJsonContains(["endingDate" => !is_null($endingDateSecond) ? $endingDateSecond.'T00:00:00+00:00' : null]);
        $this->assertJsonContains(["description" => $descriptionSecond]);

        $response = $client->request(Request::METHOD_GET, '/api/work_experiences');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
    }

    public function testCanCreateExperienceAndDeleteIt(): void
    {
        $client = static::createClient();
        $faker = Factory::create();

        $company = $faker->company();
        $role = $this->getRandomRole();
        $startingDate = $faker->dateTimeInInterval('-30 years', 'now')->format('Y-m-d');
        $endingDate = $faker->boolean() ? null : $faker->dateTimeInInterval($startingDate, 'now')->format('Y-m-d');
        $description = $faker->text();

        $response = $client->request(Request::METHOD_POST, '/api/work_experiences', [
            'json' => [
                "companyName" => $company,
                "role" => $role,
                "startingDate" => $startingDate,
                "endingDate" => $endingDate,
                "description" => $description,
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["companyName" => $company]);
        $this->assertJsonContains(["role" => $role]);
        $this->assertJsonContains(["startingDate" => $startingDate.'T00:00:00+00:00']);
        $this->assertJsonContains(["endingDate" => !is_null($endingDate) ? $endingDate.'T00:00:00+00:00' : null]);
        $this->assertJsonContains(["description" => $description]);

        // delete the entity
        $response = $client->request(Request::METHOD_DELETE, $response->toArray()['@id'], [
            'json' => []
        ]);
        $this->assertResponseIsSuccessful();

        $response = $client->request(Request::METHOD_GET, '/api/work_experiences');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 0]);
    }

    public function testCannotUsePatchRequest(): void
    {
        $client = static::createClient();
        $faker = Factory::create();

        $company = $faker->company();
        $role = $this->getRandomRole();
        $startingDate = $faker->dateTimeInInterval('-30 years', 'now')->format('Y-m-d');
        $endingDate = $faker->boolean() ? null : $faker->dateTimeInInterval($startingDate, 'now')->format('Y-m-d');
        $description = $faker->text();

        $response = $client->request(Request::METHOD_POST, '/api/work_experiences', [
            'json' => [
                "companyName" => $company,
                "role" => $role,
                "startingDate" => $startingDate,
                "endingDate" => $endingDate,
                "description" => $description,
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $secondRole = $this->getRandomRole();
        $response = $client->request(Request::METHOD_PATCH, $response->toArray()['@id'], [
            'json' => [
                "role" => $secondRole,
            ]
        ]);

        $this->assertResponseStatusCodeSame(415);
    }

    private function getRandomRole()
    {
        $roles = [
            'Backend Developer',
            'Frontend Developer',
            'Fullstack Developer',
            'Principal Developer',
            'Team Lead',
        ];

        return $this->getRandomItem($roles);
    }
}
