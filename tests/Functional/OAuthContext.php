<?php

namespace Tests\Functional;

use App\Kernel;
use Behat\Behat\Context\Context;
use Behat\Hook\AfterScenario;
use Behat\Hook\BeforeScenario;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OAuthContext extends WebTestCase implements Context
{
    use CrawlerCapabilities;
    use HttpCapabilities;
    use SecurityCapabilities;

    private ?KernelBrowser $client;

    public static function getKernelClass(): string
    {
        return Kernel::class;
    }

    #[BeforeScenario]
    public function setUpClient(): void
    {
        $this->client = self::createClient();
        $this->accessToken = null;
    }

    #[AfterScenario]
    public function tearDownClient(): void
    {
        $this->client = null;
        $this->response = null;
        parent::tearDown();
    }
}
