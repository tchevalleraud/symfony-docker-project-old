<?php
    namespace App\UI\API;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;

    class APIExtendController extends AbstractController {

        public function getPaginate($data){
            return [
                "count"         => sizeof($data->getItems()),
                "page"          => $data->getCurrentPageNumber(),
                "total_count"   => $data->getTotalItemCount(),
                "total_page"    => (int)ceil($data->getTotalItemCount() / $data->getItemNumberPerPage())
            ];
        }

        public function getResponse($code = 200, $message = "OK"){
            return [
                "code"      => $code,
                "datetime"  => new \DateTime(),
                "message"   => $message
            ];
        }

        public function renderAPI($data = [], $code = 200){
            if($code == 200) {
                $response   = ['code' => 200, 'message' => 'OK'];
                $return     = Response::HTTP_OK;
            } else {
                $response = ['code' => 501, 'message' => 'Not Implemented'];
                $return     = Response::HTTP_NOT_IMPLEMENTED;
            }

            return new JsonResponse([
                'data'      => $data,
                'datetime'  => new \DateTime(),
                'response'  => $response
            ], $return);
        }

    }