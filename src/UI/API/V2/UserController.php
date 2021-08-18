<?php
    namespace App\UI\API\V2;

    use App\Domain\_mysql\System\Repository\UserRepository;
    use App\UI\API\APIExtendController;
    use OpenApi\Annotations as OA;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("", name="users.")
     */
    class UserController extends APIExtendController {

        /**
         * @Route("/users", name="all", methods={"GET"})
         * @OA\Get(
         *     path="/users",
         *     security={{"apiKeyAuth":{}}, {"bearerAuth":{}}},
         *     tags={"User"},
         *     @OA\Parameter(name="limit", description="Page size", in="query", required=false, @OA\Schema(type="integer", default="10")),
         *     @OA\Parameter(name="page", description="Page number", in="query", required=false, @OA\Schema(type="integer", default="1")),
         *     @OA\Response(
         *         response=200,
         *         description="200 - OK",
         *         @OA\JsonContent(
         *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SystemUser")),
         *             @OA\Property(property="response", type="array", @OA\Items(), ref="#/components/schemas/Response200")
         *         )
         *     ),
         *     @OA\Response(response="400", description="Bad Request", ref="#/components/responses/BadRequest"),
         *     @OA\Response(response="401", description="Unauthorized", ref="#/components/responses/Unauthorized"),
         *     @OA\Response(response="500", description="Internal Server Error", ref="#/components/responses/InternalServerError")
         * )
         */
        public function users(Request $request, UserRepository $userRepository){
            $users = $userRepository->findAll();

            $data = [];
            foreach ($users as $user){
                $data[] = [
                    'id'    => $user->getId(),
                    'email' => $user->getEmail()
                ];
            }

            return new JsonResponse([
                'data'      => $data,
                'response'  => $this->getResponse()
            ]);
        }

    }