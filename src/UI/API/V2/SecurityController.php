<?php
    namespace App\UI\API\V2;

    use App\Domain\_mysql\System\Repository\UserRepository;
    use App\UI\API\APIExtendController;
    use Firebase\JWT\JWT;
    use OpenApi\Annotations as OA;
    use Symfony\Component\Config\FileLocator;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
    use Symfony\Component\Security\Core\Exception\UserNotFoundException;

    /**
     * @Route("", name="security.")
     */
    class SecurityController extends APIExtendController {

        /**
         * @Route("/login", name="login", methods={"POST"})
         * @OA\Post(
         *     path="/login",
         *     summary="Basic authentication",
         *     tags={"Authentication"},
         *     @OA\RequestBody(
         *         description="Login request body",
         *         required=true,
         *         @OA\JsonContent(
         *             ref="#/components/schemas/RequestLogin"
         *         )
         *     ),
         *     @OA\Response(
         *         response="200",
         *         description="200 - OK",
         *         @OA\JsonContent(
         *             @OA\Property(property="token", type="string"),
         *             @OA\Property(property="response", type="array", @OA\Items(), ref="#/components/schemas/Response200")
         *         )
         *     )
         * )
         */
        public function login(Request $request, UserRepository $userRepository, EncoderFactoryInterface $encoderFactory){
            $requestLogin = $request->toArray();
            if(array_key_exists("username", $requestLogin) && array_key_exists("password", $requestLogin)){
                $user = $userRepository->findOneBy(['email' => $requestLogin['username']]);
                if(!$user) throw new UserNotFoundException();

                $encoder = $encoderFactory->getEncoder($user);
                if($encoder->isPasswordValid($user->getPassword(), $requestLogin['password'], null)){
                    $fileLocator = new FileLocator([__DIR__.'/../../../../config/jwt/']);

                    $privateKey = file_get_contents($fileLocator->locate("private_key.pem", null, false)[0]);

                    return new JsonResponse([
                        'token'     => JWT::encode(["token" => $user->getApiToken(), "date" => (new \DateTime())->format("d/m/Y H:i:s")], $privateKey, 'RS256'),
                        'response'  => $this->getResponse()
                    ]);
                } else dd("nok");
            } else dd("nok");
        }

    }