<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller\User\RegisterController\Request;

use App\Tests\Functional\FunctionalTestCase;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Symfony\Component\DomCrawler\Crawler;

class SuccessTest extends FunctionalTestCase
{
    public function testSuccess(): void
    {
        $registerCrawler = $this->client->request('GET', '/cabinet/register');
        $registerForm = $registerCrawler->filter('form')->form([
            'form[name]' => 'Kim Jon Un',
            'form[email]' => 'kim@app.test',
            'form[password]' => 'kim1password',
            'form[timezone]' => 'Asia/Pyongyang',
        ]);
        $this->client->submit($registerForm);

        $this->assertTrue($this->client->getResponse()->isRedirect());


        $this->client->followRedirect();

        $this->assertSelectorTextContains('div', 'Confirm you E-Mail: kim@app.test');

        $ch = curl_init('http://cat-progress-mailhog:8025/api/v2/search?kind=containing&query=' . urlencode('Confirm E-Mail'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        $messagesData = json_decode($result, true, 512, JSON_THROW_ON_ERROR);

        $this->assertTrue($messagesData['total'] === 1);

        $mailBody = quoted_printable_decode($messagesData['items'][0]['Content']['Body']);

        $mailCrawler = new Crawler();
        $mailCrawler->addHTMLContent($mailBody);
        $confirmLink = $mailCrawler->filter('body a')->attr('href');

        $confirmPath = str_replace('http://localhost', '', $confirmLink);

        $this->client->request('GET', $confirmPath);
        $loginCrawler = $this->client->followRedirect();
        $loginForm = $loginCrawler->filter('form')->form([
            'email' => 'kim@app.test',
            'password' => 'kim1password',
        ]);
        $this->client->submit($loginForm);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
