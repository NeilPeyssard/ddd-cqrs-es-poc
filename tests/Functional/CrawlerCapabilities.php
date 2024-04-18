<?php

namespace Tests\Functional;

use Behat\Gherkin\Node\TableNode;
use Behat\Step\Then;

trait CrawlerCapabilities
{
    #[Then('I should see a :selector with label :text')]
    public function iShouldSeeAnElementWithLabel(string $selector, string $text): void
    {
        $this->assertSelectorExists($selector);
        $this->assertSelectorTextContains($selector, $text);
    }

    #[Then('I should see an :tag with attributes:')]
    public function iShouldSeeAnElementWithAttributes(string $tag, TableNode $attributes): void
    {
        $crawler = $this->client->getCrawler();
        $hasAnElement = false;

        foreach ($crawler->filter($tag) as $element) {
            $matchAll = true;

            foreach ($attributes as $attribute) {
                if (!$element->hasAttribute($attribute['attr']) || $attribute['value'] !== $element->getAttribute($attribute['attr'])) {
                    $matchAll = false;

                    break;
                }
            }

            if ($matchAll) {
                $hasAnElement = true;

                break;
            }
        }

        $this->assertTrue($hasAnElement);
    }
}
