<?php
    namespace App\Tests\_extend;

    use App\Domain\_mysql\System\Entity\User;
    use Facebook\WebDriver\WebDriverDimension;
    use Symfony\Component\BrowserKit\Cookie;
    use Symfony\Component\Panther\Client;
    use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

    trait PantherTestCaseExtend {

        public function getPantherClient(){
            $client = static::createPantherClient([
                'external_base_uri' => 'http://php',
                'readinessPath' => '/en/login.html'
            ]);
            $client->manage()->window()->setSize(new WebDriverDimension(1920, 1080));
            return $client;
        }

        public function getAuthenticatedPantherClient($client, $email){
            $client->request('GET', '/en/login.html');

            $em     = self::$container->get('doctrine')->getManager();
            $user   = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if($user) {
                $session    = self::$container->get('session');
                $token      = new PostAuthenticationToken($user, 'main',$user->getRoles());

                $session->set('_security_main', serialize($token));
                $session->save();

                $cookie = new Cookie($session->getName(), $session->getId());
                $client->getCookieJar()->set($cookie);
            } else throw new \Exception('User not exist');

            return $client;
        }

        public function takeScreenshot(Client $client, $request = "/", $name = "root.png"){
            $this->takeScreenshotSize($client, $request, $name, "pc", 2560, 1440);
            $this->takeScreenshotSize($client, $request, $name, "pc", 1920, 1080);
            $this->takeScreenshotSize($client, $request, $name, "pc", 1440, 900);
            $this->takeScreenshotSize($client, $request, $name, "mobile", 414, 736);
            $this->takeScreenshotSize($client, $request, $name, "mobile", 375, 812);
        }

        private function takeScreenshotSize(Client $client, $request = "/", $name = "root.png", $type = "pc", $width = 1920, $height = 1080){
            $client->manage()->window()->setSize(new WebDriverDimension($width, $height));
            $client->request('GET', $request);

            $this->assertStringContainsString($request, $client->getCurrentURL());

            $client->takeScreenshot("screenshot/".$type."-".$width."x".$height."/".$name);
        }

    }