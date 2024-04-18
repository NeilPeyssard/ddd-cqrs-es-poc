<?php

namespace Tests\Functional;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\Then;
use Behat\Step\When;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

trait HttpCapabilities
{
    private ?KernelBrowser $client;
    private ?Response $response;
    private ?string $accessToken;

    #[When('I request :url with method :method')]
    public function iRequestWithMethod(string $url, string $method): void
    {
        $this->request($url, $method);
    }

    #[When('I request :url with method :method and Content-Type :contentType')]
    public function iRequestWithMethodAndContentType(string $url, string $method, string $contentType): void
    {
        $this->request($url, $method, $contentType);
    }

    #[When('I request :url with method :method and Content-Type :contentType and body:')]
    public function iRequestWithMethodAndContentTypeAndBody(string $url, string $method, string $contentType, PyStringNode $body): void
    {
        $this->request($url, $method, $contentType, $body);
    }

    #[Then('The response status code should be :status')]
    public function theResponseStatusCodeShouldBe(int $status): void
    {
        $this->assertEquals($status, $this->response->getStatusCode());
    }

    #[Then('I should be redirect to uri :uri')]
    public function iShouldBeRedirectToUri(string $uri): void
    {
        $this->isRedirectResponse();
        $this->assertMatchesRegularExpression(sprintf('#%s$#', $uri), $this->response->headers->get('Location'));
    }

    #[Then('I should be redirect to an url which match :pattern')]
    public function iShouldBeRedirectToTheDomainWithTheScheme(string $pattern): void
    {
        $this->isRedirectResponse();
        $this->assertMatchesRegularExpression(sprintf('#%s$#', $pattern), $this->response->headers->get('Location'));
    }

    #[Then('I should receive a valid JSON response')]
    public function iShouldReceivedAValidJsonResponse(): void
    {
        $this->assertTrue(json_validate($this->response->getContent()));
    }

    #[Then('The JSON node :key should not be null')]
    public function theJsonNodeShouldNotBeNull($key): void
    {
        $data = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey($key, $data);
        $this->assertNotNull($data[$key]);
    }

    #[Then('The JSON node :key should be equal to :value')]
    public function theJsonNodeShouldBeEqualTo($key, $value): void
    {
        $data = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey($key, $data);
        $this->assertEquals($value, $data[$key]);
    }

    private function isRedirectResponse(): void
    {
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertTrue($this->response->headers->has('Location'));
    }

    private function request(string $url, string $method, string $contentType = null, PyStringNode $body = null): void
    {
        $body = $body ? json_decode($body->getRaw(), true) : [];
        $headers = $contentType ? ['HTTP_CONTENT_TYPE' => $contentType] : [];

        if ($this->accessToken) {
            $headers['HTTP_AUTHORIZATION'] = sprintf('Bearer %s', $this->accessToken);
        }

        $this->client->request($method, $url, $body, [], $headers);
        $this->response = $this->client->getResponse();
    }
}
