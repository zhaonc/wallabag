<?php

namespace App\Tests\Functionnal\Resource;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase as ApiPlatformTestCase;

class AccurateTest extends ApiPlatformTestCase
{
    public function testPostNewArticle()
    {
        $client = static::createClient();
        $headers = [];

        $options = [
            'headers' => $headers,
            'json' => [
                'url' => 'https://wallabag.org/en/news/wallabag-v2',
            ]
        ];

        $responseArray = $client->request('POST', 'http://localhost:4000/api/articles', $options)->toArray();
        $this->assertResponseStatusCodeSame(201);

        $this->assertEquals('Three years after the first commit, wallabag 2.0.0', $responseArray['title']);
    }
}