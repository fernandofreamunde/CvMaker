<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Faker\Factory;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;

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
                "content" => $email
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["content" => $email]);

        $response = $client->request(Request::METHOD_GET, '/api/contacts');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
    }

    public function testCanCreateContactAndUpdateIt(): void
    {
        $client = static::createClient();
        $faker = Factory::create();
        $initialEmail = $faker->email();
        $response = $client->request(Request::METHOD_POST, '/api/contacts', [
            'json' => [
                "name" => "email",
                "content" => $initialEmail
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["content" => $initialEmail]);

        $secondEmail = $faker->email();
        $response = $client->request(Request::METHOD_PUT, $response->toArray()['@id'], [
            'json' => [
                "name" => "email",
                "content" => $secondEmail
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
                "content" => $email
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
                "content" => $email
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["content" => $email]);

        $secondEmail = $faker->email();
        $response = $client->request(Request::METHOD_PATCH, $response->toArray()['@id'], [
            'json' => [
                "content" => $secondEmail
            ]
        ]);

        $this->assertResponseStatusCodeSame(405);
    }
}
