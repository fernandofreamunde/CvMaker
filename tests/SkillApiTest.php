<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Faker\Factory;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;

class SkillApiTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testSkillsCanBeListed(): void
    {
        $response = static::createClient()->request(Request::METHOD_GET, '/api/skills');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/skills']);
    }

    public function testCanCreateSkillsAndRead(): void
    {
        $client = static::createClient();

        $response = $client->request(Request::METHOD_POST, '/api/skills', [
            'json' => [
                "type" => "string",
                "content" => "string",
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains(["hydra:description" => "Invalid Skill Type."]);

        $response = $client->request(Request::METHOD_POST, '/api/skills', [
            'json' => [
                "type" => "programming language",
                "content" => "php",
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["type" => "programming language"]);
        $this->assertJsonContains(["content" => "php"]);

        $response = $client->request(Request::METHOD_GET, '/api/skills');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
    }

    public function testCanCreateSkillAndUpdateIt(): void
    {
        $client = static::createClient();

        $response = $client->request(Request::METHOD_POST, '/api/skills', [
            'json' => [
                "type" => "programming language",
                "content" => "javascript",
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["type" => "programming language"]);
        $this->assertJsonContains(["content" => "javascript"]);

        $response = $client->request(Request::METHOD_PUT, $response->toArray()['@id'], [
            'json' => [
                "type" => "language",
                "content" => "portuguese",
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["type" => "language"]);
        $this->assertJsonContains(["content" => "portuguese"]);

        $response = $client->request(Request::METHOD_GET, '/api/skills');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);

        $this->assertSame('language', $response->toArray()['hydra:member'][0]['type']);
        $this->assertSame('portuguese', $response->toArray()['hydra:member'][0]['content']);
    }

    public function testCanCreateSkillAndDeleteIt(): void
    {
        $client = static::createClient();

        $response = $client->request(Request::METHOD_POST, '/api/skills', [
            'json' => [
                "type" => "tool",
                "content" => "hammer",
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["type" => "tool"]);
        $this->assertJsonContains(["content" => "hammer"]);

        $response = $client->request(Request::METHOD_DELETE, $response->toArray()['@id']);

        $this->assertResponseIsSuccessful();

        $response = $client->request(Request::METHOD_GET, '/api/skills');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 0]);
    }

    public function testCannotUsePatchRequest(): void
    {
        $client = static::createClient();

        $response = $client->request(Request::METHOD_POST, '/api/skills', [
            'json' => [
                "type" => "technology",
                "content" => "docker",
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["type" => "technology"]);
        $this->assertJsonContains(["content" => "docker"]);

        $response = $client->request(Request::METHOD_PATCH, $response->toArray()['@id'], [
            'json' => [
                "content" => 'Linux',
            ]
        ]);

        $this->assertResponseStatusCodeSame(405);
    }
}
