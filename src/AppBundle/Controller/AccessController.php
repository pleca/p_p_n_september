<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\ApiInterface;
use AppBundle\Utils\RestApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/access", name="access")
 */

class AccessController extends Controller
{

    private function checkPermissionDB($uid, $aid)
    {
        $em = $this->getDoctrine()->getManager('olddb')->getConnection();
        $query = $em->prepare("CALL access_check (:aid, :uid)");
        $query->execute([":aid" => $aid, ":uid" => $uid]);
        $result = $query->fetch();

        if ($result["login"] == $uid && $result["id_uprawnienia"] == $aid) {
            return true;
        }
        else {
            return false;
        }
    }

    public function checkPermission($uid, $aid)
    {
        $result = $this->checkPermissionDB($uid, $aid);
        return $result;
    }

    /**
     * @Route("/check/{uid}/{aid}")
     */
    public function checkAccessPermission(Request $request)
    {
        $result = $this->checkPermission($request->get('uid'), $request->get('aid'));
        $result = array("permission" => $result);

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')) )
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($result);
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }



}