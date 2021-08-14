<?php
    namespace App\Tests\UI\_default\Controller;

    use App\Tests\_extend\WebTestCaseExtend;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Symfony\Component\HttpFoundation\Response;

    class RootControllerTest extends WebTestCase {

        use WebTestCaseExtend;

        public function test_Redirect(){
            $client = static::createClient();
            $client->request("GET", "/");
            $client->followRedirect();
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }

    }