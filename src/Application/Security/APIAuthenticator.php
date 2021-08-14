<?php
    namespace App\Application\Security;

    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
    use Symfony\Component\Security\Core\Exception\AuthenticationException;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Security\Core\User\UserProviderInterface;
    use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

    class APIAuthenticator extends AbstractGuardAuthenticator {

        private $em;

        public function __construct(EntityManagerInterface $em){
            $this->em = $em;
        }

        public function supports(Request $request){
            if($request->headers->has('X-AUTH-TOKEN')) return $request->headers->has('X-AUTH-TOKEN');
            elseif($request->server->has('X-AUTH-TOKEN')) return $request->server->has('X-AUTH-TOKEN');
            else {
                return true;
            }
        }

        public function getCredentials(Request $request){
            if($request->headers->has('X-AUTH-TOKEN')) return $request->headers->get('X-AUTH-TOKEN');
            elseif($request->server->has('X-AUTH-TOKEN')) return $request->server->get('X-AUTH-TOKEN');
            else return "NULL";
        }

        public function getUser($credentials, UserProviderInterface $userProvider){
            if(null === $credentials){
                return null;
            }

            return $userProvider->loadUserByIdentifier($credentials);
        }

        public function checkCredentials($credentials, UserInterface $user){
            return true;
        }

        public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey){
            return null;
        }

        public function onAuthenticationFailure(Request $request, AuthenticationException $exception){
            $data = [
                'code'      => 401,
                'message'   => "Unauthorized"
            ];
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        public function start(Request $request, AuthenticationException $authException = null){
            $data = [
                'message'   => "Unauthorized"
            ];
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        public function supportsRememberMe(){
            return false;
        }

    }