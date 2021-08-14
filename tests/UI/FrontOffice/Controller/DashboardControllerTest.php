<?php
    namespace App\Tests\UI\FrontOffice\Controller;

    use App\Tests\_extend\WebTestCaseExtend;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Response;

    class DashboardControllerTest extends WebTestCase {

        use WebTestCaseExtend;

        public function test_EN_Index(){
            $client = static::createClient();
            $this->login($client, "thibault.chevalleraud@gmail.com");
            $client->request("GET", "/en/dashboard.html");
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }

        public function test_FR_Index(){
            $client = static::createClient();
            $this->login($client, "thibault.chevalleraud@gmail.com");
            $client->request("GET", "/fr/dashboard.html");
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }

    }