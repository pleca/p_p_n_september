<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\ApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function apiTest(Request $request)
    {
        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            if (isset($this->getParameter('api_key')[$request->get('api_key')][1])) {
                $user = $this->getParameter('api_key')[$request->get('api_key')][1];
            }
            else {
                $user = null;
            }

            $status = array(
                            "status"=>"test ok",
                            "key"=>$request->get('api_key'),
                            "user"=>$user,
                            "IP"=>$_SERVER['REMOTE_ADDR'],
                            "message"=>"Have a nice day",
                            );
            return new JsonResponse($status);
        }
        else
        {
                return new JsonResponse($api->apiKeyError(), 401);
        }
    }
}
