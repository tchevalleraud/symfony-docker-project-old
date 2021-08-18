<?php
    namespace App\UI\API\V2\Account;

    use App\Domain\_mysql\System\Entity\App;
    use App\Domain\_mysql\System\Entity\User;
    use App\UI\API\APIExtendController;
    use OpenApi\Annotations as OA;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/account/", name="account.")
     */
    class AccountController extends APIExtendController {

        /**
         * @Route("info", name="info", methods={"GET"})
         * @OA\Get(
         *     path="/account/info",
         *     security={{"apiKeyAuth":{}}, {"bearerAuth":{}}},
         *     tags={"Account"},
         *     @OA\Response(
         *         response=200,
         *         description="200 - OK",
         *         @OA\JsonContent(
         *             @OA\Property(property="app", ref="#/components/schemas/SystemApp"),
         *             @OA\Property(property="user", ref="#/components/schemas/SystemUser"),
         *             @OA\Property(property="response", type="array", @OA\Items(), ref="#/components/schemas/Response200")
         *         )
         *     ),
         *     @OA\Response(response="400", description="Bad Request", ref="#/components/responses/BadRequest"),
         *     @OA\Response(response="401", description="Unauthorized", ref="#/components/responses/Unauthorized"),
         *     @OA\Response(response="500", description="Internal Server Error", ref="#/components/responses/InternalServerError")
         * )
         */
        public function users(){
            $app  = [];
            $user = [];

            if(is_a($this->getUser(), App::class)) {
                $app = [
                    "id"        => $this->getUser()->getId(),
                    "name"      => $this->getUser()->getName(),
                    "roles"     => $this->getUser()->getRoles(),
                    "apiToken"  => $this->getUser()->getApiToken()
                ];
            } elseif(is_a($this->getUser(), User::class)){
                $user = [
                    "email" => $this->getUser()->getEmail()
                ];
            }
            return new JsonResponse([
                'app'       => $app,
                'user'      => $user,
                'response'  => $this->getResponse()
            ]);
        }

    }