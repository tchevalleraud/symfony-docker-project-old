<?php
namespace App\Tests\_extend;

use App\Domain\_mysql\System\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait WebTestCaseExtend {

    public function login(KernelBrowser $client, $email){
        $em     = static::$container->get('doctrine')->getManager();
        $user   = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if($user){
            $session    = static::$container->get('session');
            $token      = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $session->set('_security_main', serialize($token));
            $session->save();

            $cookie     = new Cookie($session->getName(), $session->getId());

            $client->getCookieJar()->set($cookie);
        } else throw new \Exception('User not exist');
    }

    public function getUserAPIToken(KernelBrowser $client, $email){
        $em     = static::$container->get('doctrine')->getManager();
        $user   = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if($user){
            return $user->getApiToken();
        } else throw new \Exception('User not exist');
    }

    public function requestAPI(KernelBrowser $client, $method, $url, $token){
        $client->request($method, $url, [], [], [], [
            'X-AUTH-TOKEN'  => $token
        ]);
    }

}