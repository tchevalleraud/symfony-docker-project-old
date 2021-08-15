<?php
    namespace App\Tests\UI\FrontOffice\Panther;

    use App\Tests\_extend\PantherTestCaseExtend;
    use Symfony\Component\Panther\PantherTestCase;

    /**
     * @group panther
     */
    class DashboardControllerTest extends PantherTestCase {

        use PantherTestCaseExtend;

        public function test_EN_Index(){
            $client = $this->getAuthenticatedPantherClient($this->getPantherClient(), "admin@pwsb.fr");
            $client->request("GET", "/en/dashboard.html");
            $this->assertStringContainsString("/en/dashboard.html", $client->getCurrentURL());
            $this->assertSelectorTextContains("h1", "Dashboard");
        }

    }