<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\RestApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * @Route("/Commission")
 */
class CommissionController extends Controller
{

    private function apiConnector($url)
    {
        $restAPI = new RestApiInterface();
        $restAPI->setUrl($url);
        $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
        $restAPI->rawApiConnector();
        if ($restAPI->getHttpCode() == 200) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse(json_decode($restAPI->getHttpResponse()));
        } else {
            return new JsonResponse(array("error" => $restAPI->getHttpCode()));
        }
    }

    /**
     * @Route("/GetSumForAgentNumberMonthAndYear/Agent/{agentNumber}/Month/{month}/Year/{year}")
     */
    public function GetSumForAgentNumberMonthAndYear(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('agentNumber').'/Commisions/Sum/Year/'.intval($request->get('year')).'/Month/'.intval($request->get('month')));
    }


    /**
     * @Route("/GetLinesForMonthWithMonthNumber/Agent/{agentNumber}/Type/{type}/Month/{month}/Year/{year}")
     */
    public function GetLinesForMonthWithMonthNumber(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('agentNumber').'/Commisions/Lines/Type/'.intval($request->get('type')).'/Year/'.intval($request->get('year')).'/Month/'.intval($request->get('month')));
    }


    /**
     * @Route("/GetCommissionsType")
     */
    public function GetCommissionsType(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url').'Agent/XXX/Commisions/CommissionTypes');
    }


    /**
     * @Route("/GetAllSumForStructByYearMonthAndAgentNumber/Agent/{agentNumber}/Month/{month}/Year/{year}")
     */
    public function GetAllSumForStructByYearMonthAndAgentNumber(Request $request)
    {
        return new JsonResponse(array("error" => "no c# endpoint provided"));
    }


    /**
     * @Route("/GetCommissionsTypeForAgentNumberAndYear/Agent/{agentNumber}/Year/{year}")
     */
    public function GetCommissionsTypeForAgentNumberAndYear(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('agentNumber').'/Commisions/Types/Year/'.intval($request->get('year')));
    }

    /**
     * @Route("/GetAllSumForAgentNumberAndYear/Agent/{agentNumber}/Type/{type}Year/{year}")
     */
    public function GetAllSumForAgentNumberAndYear(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('agentNumber').'/Commisions/Sum/Type/'.intval($request->get('type').'/Year/'.intval($request->get('year'))));
    }


    /**
     * @Route("/GetAllSumForLastTwelveMonhtsByAgentNumber/Agent/{agentNumber}/Type/{type}")
     */
    public function GetAllSumForLastTwelveMonhtsByAgentNumber(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('agentNumber').'/CommisionsSum/LastTwelveMonths/Type/'.intval($request->get('type')));
    }

    /**
     * @Route("/GetAllSumForStructByYearAndAgentNumber/Agent/{agentNumber}/Type/{type}/Year/{year}")
     */
    public function GetAllSumForStructByYearAndAgentNumber(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('agentNumber').'/CommisionsSum/Struct/Sum/Type/'.intval($request->get('type').'/Year/'.intval($request->get('year'))));
    }

    /**
     * @Route("/GetAllSumForStructByYearAndAgentNumberAll/Agent/{agentNumber}/Type/{type}/Year/{year}")
     */
    public function GetAllSumForStructByYearAndAgentNumberAll(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('agentNumber').'/CommisionsSum/Struct/SumForAll/'.intval($request->get('type').'/Year/'.intval($request->get('year'))));
    }

    /**
     * @Route("/GetCommissionsTypeForStructByAgentNumberAndYear/Agent/{agentNumber}/Year/{year}")
     */
    public function GetCommissionsTypeForStructByAgentNumberAndYear(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('agentNumber').'/CommisionsSum/Struct/Types/Year/'.intval($request->get('year')));
    }
}




