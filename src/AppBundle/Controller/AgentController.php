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
 * @Route("/agent", name="agent")
 */

class AgentController extends Controller
{
    /**
    * @Route("/getdependentstructure/{idAgent}")
    */
    public function getDependentStructure(Request $request)
    {
        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $restAPI = new RestApiInterface();
            $restAPI->setUrl($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('idAgent').'/DependentStructure');
            $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
            $restAPI->rawApiConnector();
            if ($restAPI->getHttpCode() == 200)
            {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse(json_decode($restAPI->getHttpResponse()));
            }
            else
            {
                return new JsonResponse(array("error"=>$restAPI->getHttpCode()));
            }
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }

    /**
     * @Route("/getfiletypedoctionary")
     */
    public function getFileTypeDictionary(Request $request)
    {
        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $restAPI = new RestApiInterface();
            $restAPI->setUrl($this->getParameter('rest_api_agent_url').'FileTypeDictionary');
            $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
            $restAPI->rawApiConnector();
            if ($restAPI->getHttpCode() == 200)
            {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse(json_decode($restAPI->getHttpResponse()));
            }
            else
            {
                return new JsonResponse(array("error"=>$restAPI->getHttpCode()));
            }
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }

    /**
     * @Route("/getsuperior")
     */
    public function getSuperiorStructure(Request $request)
    {
        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $restAPI = new RestApiInterface();
            $restAPI->setUrl($this->getParameter('rest_api_agent_url').'Superior');
            $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
            $restAPI->rawApiConnector();
            if ($restAPI->getHttpCode() == 200)
            {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse(json_decode($restAPI->getHttpResponse()));
            }
            else
            {
                return new JsonResponse(array("error"=>$restAPI->getHttpCode()));
            }
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }

    /**
     * @Route("/getunit", name="getunit")
     */
    public function getUnit(Request $request)
    {
        $restAPI = new RestApiInterface();
        $restAPI->setUrl($this->getParameter('rest_api_agent_url').'/Units');
        $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
        $restAPI->setPostFlag(0);
        $restAPI->rawApiConnector();
        $returnData = $restAPI->getHttpResponse();

        if ($restAPI->getHttpCode() == 200) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse(json_decode($returnData, true), 200);
        }
        else {
            $returnData = array("error"=>"null data returned");
            return new Response(json_encode($returnData, true), 404);
        }
    }

    /**
     * @Route("/getconsultantnumber", name="getconsultantnumber")
     */

    public function getConsultantNumber(Request $request)
    {
        $restAPI = new RestApiInterface();
        $restAPI->setUrl($this->getParameter('rest_api_agent_url').'/Consultants');
        $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
        $restAPI->setPostFlag(0);
        $restAPI->rawApiConnector();
        $returnData = $restAPI->getHttpResponse();
        if ($restAPI->getHttpCode() == 200) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse(json_decode($returnData, true), 200);
        }
        else {
            $returnData = array("error"=>"null data returned");
            return new Response(json_encode($returnData, true), 404);
        }

    }

}