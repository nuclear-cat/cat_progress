<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller\User\ResetPasswordController;

use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\DomCrawler\Crawler;

class SuccessTest extends FunctionalTestCase
{
    public function testSuccess(): void
    {
        // Request
        $crawler = $this->client->request('GET', 'cabinet/reset_password');
        $resetForm = $crawler->filter('form')->form([
            'form[email]' => 'donald.trump@app.test',
        ]);

        $this->client->submit($resetForm);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        // Mailhog
        $ch = curl_init('http://cat-progress-mailhog:8025/api/v2/search?kind=containing&query=' . urlencode('Recover password'));
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

        // Confirm
        $confirmCrawler = $this->client->request('GET', $confirmPath);
        $confirmForm = $confirmCrawler->filter('form')->form([
            'form[password]' => 'new_password',
            'form[repeatPassword]' => 'new_password',
        ]);
        $this->client->submit($confirmForm);

        $this->assertTrue($this->client->getResponse()->isRedirect());

        // Login
        $loginCrawler = $this->client->followRedirect();
        $loginForm = $loginCrawler->filter('form')->form([
            'email' => 'donald.trump@app.test',
            'password' => 'new_password',
        ]);

        $this->client->submit($loginForm);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
