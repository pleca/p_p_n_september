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
 * @Route("/conversation", name="conversation")
 */

class ConversationController extends Controller
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
     * @Route("/get/{agentID}")
     */
    public function getConversation(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url') . 'Agent/'.$request->get('agentID').'/Conversations');
    }

    /**
     * @Route("/getdetails/{agentID}/{conversationID}")
     */
    public function getConversationDetails(Request $request)
    {
        return $this->apiConnector($this->getParameter('rest_api_agent_url') . 'Agent/'.$request->get('agentID').'/Conversations'.$request->get('conversationID'.'/Messages'));
    }
	
    /**
     * @Route("/addconversation/{agentID}")
     */
    public function addConversation(Request $request)
    {
         $req = $request->request->all();
         $req = json_decode($req["conversation"], true);
        if (isset($req)) {
            $postFields = array("subject" => $req[0]["subject"]);
            $restAPI = new RestApiInterface();
            $restAPI->setUrl($this->getParameter('rest_api_agent_url') . 'Agent/'.$request->get('agentID').'/Conversations');
            $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
            $restAPI->setPostFlag(1);
            $restAPI->setPostFields($postFields);
            $restAPI->rawApiConnector();
            $message = $restAPI->getHttpResponse();
            $message = json_decode($message, true);
            $content = $req[0]['message'];
            $content = $content[0];
            $postFields = array("content" =>$content["content"]);

            $restAPIsec = new RestApiInterface();
            $restAPIsec->setUrl($this->getParameter('rest_api_agent_url') . 'Agent/'.$request->get('agentID').'/Conversations'.$request->get('conversationID'.'/Messages'));
            $restAPIsec->setApiKey($this->getParameter('rest_api_agent_key'));
            $restAPIsec->setPostFlag(1);
            $restAPIsec->setPostFields($postFields);
            $restAPIsec->rawApiConnector();
            if ($restAPIsec->getHttpCode() == 201) {
                $returnData = array("status" => "OK");
                return new Response(json_encode($returnData, true), 201);
            } else {
                $returnData = array("status" => "FAIL");
                return new Response(json_encode($returnData, true), 404);
            }
        }
    }

    /**
     * @Route("/addmessage/{agentID}/{conversationID}")
     */
    public function addMessage(Request $request)
    {
        $req = $request->request->all();
        $data = json_decode($req["conversation"], true);
        $conversationId = $data["conversationId"];
        $message = $data["content"];
        $postFields = array("content" => $message);

        $restAPI = new RestApiInterface();
        $restAPI->setUrl($this->getParameter('rest_api_agent_url') . 'Agent/'.$request->get('agentID').'/Conversations'.$request->get('conversationID'.'/Messages'));
        $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
        $restAPI->setPostFlag(1);
        $restAPI->setPostFields($postFields);
        $restAPI->rawApiConnector();

        if ($restAPI->getHttpCode() == 201) {
            $returnData = array("status" => "OK");
            return new Response(json_encode($returnData, true), 200);
        } else {
            $returnData = array("status" => "FAIL");
            return new Response(json_encode($returnData, true), 404);
        }
    }
























    /**
     * @Route("/getlastmessagesmain")
     */
    public function getLastMessagesMain(Request $request)
    {
        $data = json_decode('[ { "id": 1, "date": "2018-02-15T18:23:47.0000000+01:00", "side": 1, "content": "gdzie s\u0105 te wid\u0142y" }, { "id": 2, "date": "2018-02-15T18:24:04.0000000+01:00", "side": 2, "content": "no i to chyba by by\u0142o na tyle" }, { "id": 3, "date": "2018-02-15T18:24:04.0000000+01:00", "side": 2, "content": "no i to chyba by by\u0142o na tyle" } ]');

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($data);
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }
    /**
     * @Route("/getlastconversation")
     */
    public function getLastConversation(Request $request)
    {
        $data = array(
            0 => array(
                "id" => "1",
                "subject" => "Ala ma kota",
                "caseID" => "2018/04/0226069",
                "content" => "jskjdh sdkskjdh sd sdkdskjdh sdskjh sdkjhsd skjh sskdkjh sd skdjskjdh sdskdjh sdkkjh kjh sdkkjh ",
                "date" => "2018-02-01 13:21:12"
            ),
            1 => array(
                "id" => "2",
                "subject" => "Chcemy dodatku do wypłat w IT",
                "caseID" => "2018/04/3226069",
                "content" => "ksjdhkjh sdwuwue kjwh ekwj kwkejh uid wiue wekjh dsiuwh ewkejh diwudwu ",
                "date" => "2018-02-01 13:21:12"
            ),
            2 => array(
                "id" => "3",
                "subject" => "Praca domowa: budowa gniazda dla lesnej pantery",
                "caseID" => "2018/04/06234369",
                "content" => "vxmncvb xv xcmvxncb xcvmdnfbsdmfnebr  sdmfsnfb wmensbf smdfnwbe sfmdfnb werfhs dfsj sdfmnberw fsdfmsnfb werwfskdjfh wesmdnfb s",
                "date" => "2018-02-01 13:21:12"
            ),
        );

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($data);
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }
	
	/**
     * @Route("/getconversationtarget")
     */
    public function getConversationTarget(Request $request)
    {
        $data = json_decode('[ { "id": 1, "target": "DOK" }, { "id": 2, "target": "Sprawa"}, { "id": 3, "target": "Agent" } ]');

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($data);
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }

}