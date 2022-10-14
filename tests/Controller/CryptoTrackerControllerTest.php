<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CryptoTrackerControllerTest extends WebTestCase
{
    public function testWebPageIsUp(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/crypto-tracker');

        //echo $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Crypto Tracker');
    }

    public function testaddTransaction(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/crypto-tracker');

        $form = $client->clickLink('add');

        $form->submitForm('Ajouter', [
            'form[crypto]' => 'BTC',
            'form[qty]' => 0.0007,
            'form[buying_price]' => 10
        ]);

        $crawler = $client->followRedirect();

        $crawler->assertSelectorTextSame('.crypto-list:first-child() > .crypto-abbr', 'BTC');
        $crawler->assertSelectorTextSame('.crypto-list:first-child() > .crypto-name', 'Bitcoin');
        $crawler->assertSelectorTextSame('.crypto-list:first-child() > .crypto-qty', 0.0007);
    }
}
