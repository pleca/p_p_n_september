<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\ApiInterface;
use AppBundle\Utils\RestApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


/**
 * @Route("/lead", name="leads")
 */

class LeadsController extends Controller
{
    /**
     * @Route("/list")
     */
    public function getLeads(Request $request)
    {
        $emOld = $this->getDoctrine()->getManager('olddb')->getConnection();
        $query2 = $emOld->prepare("CALL CheckAvailableUnit (:login)");
        $query2->execute([":login" => $request->get('user')]);
        $result2 = $query2->fetchAll();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $json = $serializer->serialize($result2, 'json');


        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_leads (:json_table)");
        $query->execute([":json_table" => $json]);
        $result = $query->fetchAll();

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
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

    /**
     * @Route("/list_for_agent")
     */
    public function getAgentLeads(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_agent_leads (:agent_number_tmp)");
        $query->execute([":agent_number_tmp" => $request->get('user')]);
        $result = $query->fetchAll();

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

    /**
     * @Route("/get/{id}")
     */
    public function getLead(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_lead (:lead)");
        $query->execute([":lead" => $request->get('id')]);
        $result = $query->fetchAll();

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


    /**
     * @Route("/get_event_type")
     */
    public function getEventType(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_event_type");
        $query->execute();
        $result = $query->fetchAll();

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
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

    /**
     * @Route("/get_basket")
     */
    public function getBasket(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_basket");
        $query->execute();
        $result = $query->fetchAll();

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
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

   
    /**
     * @Route("/get_status")
     */
    public function getStatus(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_status");
        $query->execute();
        $result = $query->fetchAll();

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
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


	/**
     * @Route("/get_status_auxiliary")
     */
    public function getStatusAuxiliary(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_status_auxiliary (
		:status)");
        $query->execute([
            ":status" => $request->get('status'),
			]);
        $result = $query->fetchAll();

        //$api = new ApiInterface();
        //if ( $api->checkApiKey($request->get('api_key')))
        //{
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($result);
        //}
        //else
        //{
            //return new JsonResponse($api->apiKeyError(),401);
        //}
    }
	
	/**
     * @Route("/get_competition")
     */
    public function getCompetition(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_competition");
        $query->execute();
        $result = $query->fetchAll();

        //$api = new ApiInterface();
        //if ( $api->checkApiKey($request->get('api_key')))
        //{
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($result);
        //}
        //else
        //{
            //return new JsonResponse($api->apiKeyError(),401);
        //}
    }


    /**
     * @Route("/get_unit")
     */
    public function getUnit(Request $request)
    {
        $emOld = $this->getDoctrine()->getManager('olddb')->getConnection();
        $query2 = $emOld->prepare("CALL CheckAvailableUnitToSelect (:login)");
        $query2->execute([":login" => $request->get('user')]);
        $result2 = $query2->fetchAll();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $json = $serializer->serialize($result2, 'json');

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_unit (:json_table)");
        $query->execute([":json_table" => $json]);
        $result = $query->fetchAll();

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
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

    /**
     * @Route("/add_lead")
     */
    public function addLead(Request $request)
    {

        //$dateStart = date("Y-m-d", strtotime($request->get('StartEvent').' UTC'));
        //$approximate_event = date("Y-m-d", strtotime($request->get("ApproximateEventDate").' UTC'));

        if($request->get('StartEvent') != '')
        {
            $dateStart = date("Y-m-d", strtotime($request->get('StartEvent').' UTC'));
        }
        else
        {
            $dateStart = NULL;
        }
        if($request->get('ApproximateEventDate') != '')
        {
            $approximate_event = date("Y-m-d", strtotime($request->get("ApproximateEventDate").' UTC'));
        }
        else
        {
            $approximate_event = NULL;
        }

        if($request->get("AgentNumber")=='')
        {
            $agent = $request->get("User");
        }
        else
        {
            $agent = $request->get("AgentNumber");
        }

        $agent2 = $agent;

        if(substr($agent2,0,2)=='A0')
        {
            $restAPI = new RestApiInterface();
            $restAPI->setUrl($this->getParameter('rest_api_agent_url').'Agent/'.$agent.'/Director');
            $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
            $restAPI->rawApiConnector();
            $jsonReturn = $restAPI->getHttpResponse();

            $json = json_decode($jsonReturn);

            try {
                $agentNumber = $json->agent->agentNumber;
                $agentName = $json->agent->name;
                $agentId = $json->agent->agentId;

                $directorNumber = $json->director->agentNumber;
                $directorName = $json->director->name;
                $directorId = $json->director->agentId;

            }
            catch (Exception $e)
            {
                $agentNumber = $request->get("User");
                $agentName = null;
                $agentId = null;
                $directorNumber = null;
                $directorName = null;
                $directorId = null;
            }
        }
        else
        {
            $agentNumber = $request->get("User");
            $agentName = null;
            $agentId = null;
            $directorNumber = null;
            $directorName = null;
            $directorId = null;
        }

        $em = $this->getDoctrine()->getManager()->getConnection();

        $query = $em->prepare("CALL leads_add_lead (
        :agentNumber,:agentName, :agentId, :directorNumber, :directorName, :directorId 
        ,:userLogin,:victimName,:victimSurname,:victimStreet,:victimHouse,:victimPostalCode,:victimCity,:victimPhone
        ,:victimAge ,:victimKinship ,:accidentProvince,:accidentDistrict,:accidentCommune,:unitId,:basketId,:accidentType
        ,:stateId , :stateHelperId,:competitionId,:contractNumber,:description,:startDate,:approximateDate        
        )");
        $query->execute([
            ":agentNumber" => $agentNumber,
            ":agentName" => $agentName,
            ":agentId" => $agentId,
            ":directorNumber" => $directorNumber,
            ":directorName" => $directorName,
            ":directorId" => $directorId,
            ":userLogin" => $request->get("User"),
            ":victimName" => $request->get("VictimFirstName"),
            ":victimSurname" => $request->get("VictimLastName"),
            ":victimStreet" => $request->get("VictimStreet"),
            ":victimHouse" => $request->get("VictimHome"),
            ":victimPostalCode" => $request->get("VictimPostCode"),
            ":victimCity" => $request->get("VictimCity"),
            ":victimPhone" => $request->get("VictimPhone"),
            ":victimAge" => intval($request->get("VictimAge")),
            ":victimKinship" => $request->get("Relationship"),
            ":accidentProvince" => $request->get("EventProvince"),
            ":accidentDistrict" => $request->get("EventDistrict"),
            ":accidentCommune" => $request->get("EventCommune"),
            ":unitId" => intval($request->get("UnitID")),
            ":basketId" => intval($request->get("BasketID")),
            ":accidentType" => intval($request->get("EventTypeID")),
            ":stateId" => intval($request->get("StatusID")),
            ":stateHelperId" => intval($request->get("StatusAuxiliaryID")),
            ":competitionId" => intval($request->get("CompetitionID")),
            ":contractNumber" => $request->get("Contract"),
            ":description" => $request->get("Description"),
            ":startDate" => $dateStart,
            ":approximateDate" => $approximate_event
        ]);

        $emOld = $this->getDoctrine()->getManager('olddb')->getConnection();
        $query2 = $emOld->prepare("CALL CheckAvailableUnit (:login)");
        $query2->execute([":login" => $request->get("User")]);
        $result2 = $query2->fetchAll();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $json = $serializer->serialize($result2, 'json');

        //var_dump($query->fetchAll());
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_leads (:json_table)");
        $query->execute([":json_table" => $json]);
        $result = $query->fetchAll();

        //$api = new ApiInterface();
        //if ( $api->checkApiKey($request->get('api_key')))
        //{
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();
        return new JsonResponse($result);
        //}
        //else
        //{
        //   return new JsonResponse($api->apiKeyError(),401);
        //}


        //$result = intval($request->get("UnitID"));
        //return new JsonResponse($result);


        /*$em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_leads");
        $query->execute();
        $result = $query->fetchAll();
        return new JsonResponse($result);*/

        /*

        $dateStart = date("Y-m-d", strtotime($request->get('StartEvent').' UTC'));
        //$dateEnd = date("Y-m-d", strtotime($request->get('EndEvent').' UTC'));
        $approximate_event = date("Y-m-d", strtotime($request->get("ApproximateEventDate").' UTC'));
		//var_dump($request);
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_add_lead (
                :agent_number_tmp, 
                :user_login_tmp, 
                :start_event_tmp,
                :first_name_tmp,
                :last_name_tmp,
                :age_tmp,
                :street_tmp,
                :home_tmp,
                :apartment_tmp,
                :post_code_tmp,
                :post_office_tmp,
                :city_tmp,
                :victim_province_tmp,
                :victim_district_tmp,
                :victim_commune_tmp,
                :phone_tmp,
                :email_tmp,
                :province_tmp,
                :district_tmp,
                :commune_tmp,
                :description_tmp,
                :unit_id_tmp,
                :event_type_id_tmp,
                :basket_id_tmp,
                :status_id_tmp,
                :status_auxiliary_id_tmp,
                :competition_id_tmp,
                :contract_tmp,
                :approximate_event_tmp,
                :relationship_tmp,
                :struct_id_tmp
            )");
        $query->execute([
            ":agent_number_tmp" => NULL,
            ":user_login_tmp" => $request->get("User"),
            ":start_event_tmp" => $dateStart,
            ":end_event_tmp" => $dateEnd,
            ":first_name_tmp" => $request->get("VictimFirstName"),
            ":last_name_tmp" => $request->get("VictimLastName"),
            ":age_tmp" => $request->get("VictimAge"),
            ":street_tmp" => $request->get("VictimStreet"),
            ":home_tmp" => $request->get("VictimHome"),
            ":apartment_tmp" => NULL,
            ":post_code_tmp" => $request->get("VictimPostCode"),
            ":post_office_tmp" => NULL,
            ":city_tmp" => $request->get("VictimCity"),
            ":victim_province_tmp" => NULL,
            ":victim_district_tmp" => NULL,
            ":victim_commune_tmp" => NULL,
            ":phone_tmp" => $request->get("VictimPhone"),
            ":email_tmp" => NULL,
            ":province_tmp" => $request->get("EventProvince"),
            ":district_tmp" => $request->get("EventDistrict"),
            ":commune_tmp" => $request->get("EventCommune"),
            ":description_tmp" => $request->get("Description"),
            ":unit_id_tmp" => $request->get("UnitID"),
            ":event_type_id_tmp" => $request->get("EventTypeID"),
            ":basket_id_tmp" => $request->get("BasketID"),
            ":status_id_tmp" => $request->get("StatusID"),
            ":status_auxiliary_id_tmp" => $request->get("StatusAuxiliaryID"),
            ":competition_id_tmp" => $request->get("CompetitionID"),
            ":contract_tmp" => $request->get("Contract"),
            ":approximate_event_tmp" => $approximate_event,
            ":relationship_tmp" => $request->get("Relationship"),
            ":struct_id_tmp" => $request->get("StructID")
        ]);
		//var_dump($query->fetchAll());
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_leads");
        $query->execute();
        $result = $query->fetchAll();
		
        //$api = new ApiInterface();
        //if ( $api->checkApiKey($request->get('api_key')))
        //{
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($result);
        //}
        //else
        //{
        //   return new JsonResponse($api->apiKeyError(),401);
        //}

        */
    }


    /**
     * @Route("/edit_lead")
     */
    public function editLead(Request $request)
    {
        $req = $request->request->all();
        $req = $req["models"];
        $req = json_decode($req, true);


        foreach ($req as $key => $row) {

            if($row["StartEvent"] != '')
            {
                $dateStart = date("Y-m-d", strtotime($row["StartEvent"].' UTC'));
            }
            else
            {
                $dateStart = NULL;
            }
            if($row["ApproximateEventDate"] != '')
            {
                $approximate_event = date("Y-m-d", strtotime($row["ApproximateEventDate"].' UTC'));
            }
            else
            {
                $approximate_event = NULL;
            }

            $agent = $row["AgentNumber"];

            $restAPI = new RestApiInterface();
            $restAPI->setUrl($this->getParameter('rest_api_agent_url').'Agent/'.$agent.'/Director');
            $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
            $restAPI->rawApiConnector();
            $jsonReturn = $restAPI->getHttpResponse();

            $json = json_decode($jsonReturn);

            try {
                $agentNumber = $json->agent->agentNumber;
                $agentName = $json->agent->name;
                $agentId = $json->agent->agentId;

                $directorNumber = $json->director->agentNumber;
                $directorName = $json->director->name;
                $directorId = $json->director->agentId;

            }
            catch (Exception $e)
            {
                $agentNumber = null;
                $agentName = null;
                $agentId = null;
                $directorNumber = null;
                $directorName = null;
                $directorId = null;
            }


            //$dateStart = date("Y-m-d", strtotime($row["StartEvent"].' UTC'));
            //$dateEnd = date("Y-m-d", strtotime($row["EndEvent"].' UTC'));
            //$approximate_event = date("Y-m-d", strtotime($row["ApproximateEventDate"].' UTC'));


            $em = $this->getDoctrine()->getManager()->getConnection();
            $query = $em->prepare("CALL leads_edit_lead (
            :id_tmp, 
            :user_login_tmp, 
            :agent_number_tmp, 
            :agent_name_tmp, 
            :agent_id_tmp,
            :director_number_tmp, 
            :director_name_tmp, 
            :director_id_tmp,
            :start_event_tmp, 
            :first_name_tmp, 
            :last_name_tmp,
            :age_tmp, 
            :street_tmp, 
            :home_tmp, 
            :post_code_tmp, 
            :city_tmp,
            :phone_tmp, 
            :province_tmp, 
            :district_tmp, 
            :commune_tmp, 
            :description_tmp, 
            :unit_id_tmp, 
            :event_type_id_tmp, 
            :basket_id_tmp,
            :status_id_tmp, 
            :contract_tmp, 
            :approximate_event_date_tmp, 
            :relationship_tmp, 
            :status_auxiliary_id_tmp,
            :competition_tmp
            )");


            $query->execute([
                ":id_tmp" => $row["ID"],
                ":user_login_tmp" => $request->get("user"),
                ":agent_number_tmp" => $agentNumber,
                ":agent_name_tmp" => $agentName,
                ":agent_id_tmp"=> $agentId,
                ":director_number_tmp" => $directorNumber,
                ":director_name_tmp" => $directorName,
                ":director_id_tmp" => $directorId,
                ":start_event_tmp" => $dateStart,
                ":first_name_tmp" => $row["VictimFirstName"],
                ":last_name_tmp" => $row["VictimLastName"],
                ":age_tmp" => $row["VictimAge"],
                ":street_tmp" => $row["VictimStreet"],
                ":home_tmp" => $row["VictimHome"],
                ":post_code_tmp" => $row["VictimPostCode"],
                ":city_tmp" => $row["VictimCity"],
                ":phone_tmp" => $row["VictimPhone"],
                ":province_tmp" => $row["EventProvince"],
                ":district_tmp" => $row["EventDistrict"],
                ":commune_tmp" => $row["EventCommune"],
                ":description_tmp" => $row["Description"],
                ":unit_id_tmp" => $row["UnitID"],
                ":event_type_id_tmp" => $row["EventTypeID"],
                ":basket_id_tmp" => $row["BasketID"],
                ":status_id_tmp" => $row["StatusID"],
                ":contract_tmp" => $row["Contract"],
                ":approximate_event_date_tmp" => $approximate_event,
                ":relationship_tmp" => $row["Relationship"],
                ":status_auxiliary_id_tmp" => $row["StatusAuxiliaryID"],
                ":competition_tmp" => $row["CompetitionID"]
            ]);

            /*$em = $this->getDoctrine()->getManager()->getConnection();
            $query = $em->prepare("CALL leads_edit_lead (
                :id_tmp, 
                :user_login_tmp, 
                :agent_number_tmp, 
                :start_event_tmp,              
                :first_name_tmp,
                :last_name_tmp,
                :age_tmp,
                :street_tmp,
                :home_tmp,
                :apartment_tmp,
                :post_code_tmp,
                :post_office_tmp,
                :city_tmp,
                :victim_province_tmp,
                :victim_district_tmp,
                :victim_commune_tmp,
                :phone_tmp,
                :email_tmp,
                :province_tmp,
                :district_tmp,
                :commune_tmp,
                :description_tmp,
                :unit_id_tmp,
                :event_type_id_tmp,
                :basket_id_tmp,
                :status_id_tmp,
				:status_auxiliary_id_tmp,
				:competition_id_tmp,
                :contract_tmp,
                :approximate_event_tmp,
                :relationship_tmp,
                :struct_id_tmp
            )");
            $query->execute([
                ":id_tmp" => $row["ID"],
                ":user_login_tmp" => $request->get("user"),
                ":agent_number_tmp" => $row["AgentNumber"],
                ":start_event_tmp" => $dateStart,
                ":first_name_tmp" => $row["VictimFirstName"],
                ":last_name_tmp" => $row["VictimLastName"],
                ":age_tmp" => $row["VictimAge"],
                ":street_tmp" => $row["VictimStreet"],
                ":home_tmp" => $row["VictimHome"],
                ":apartment_tmp" => NULL,
                ":post_code_tmp" => $row["VictimPostCode"],
                ":post_office_tmp" => NULL,
                ":city_tmp" => $row["VictimCity"],
                ":victim_province_tmp" => NULL,
                ":victim_district_tmp" => NULL,
                ":victim_commune_tmp" => NULL,
                ":phone_tmp" => $row["VictimPhone"],
                ":email_tmp" => NULL,
                ":province_tmp" => $row["EventProvince"],
                ":district_tmp" => $row["EventDistrict"],
                ":commune_tmp" => $row["EventCommune"],
                ":description_tmp" => $row["Description"],
                ":unit_id_tmp" => $row["UnitID"],
                ":event_type_id_tmp" => $row["EventTypeID"],
                ":basket_id_tmp" => $row["BasketID"],
                ":status_id_tmp" => $row["StatusID"],
				":status_auxiliary_id_tmp" => $row["StatusAuxiliaryID"],
				":competition_id_tmp" => $row["CompetitionID"],
                ":contract_tmp" => $row["Contract"],
                ":approximate_event_tmp" => $approximate_event,
                ":relationship_tmp" =>  $row["Relationship"],
                ":struct_id_tmp" =>  $row["StructID"]
            ]);*/

        }

        foreach ($req as $key => $row) {
            $em = $this->getDoctrine()->getManager()->getConnection();
            $query = $em->prepare("CALL leads_get_lead (:lead)");
            $query->execute([":lead" => $row["ID"]]);
            $result = $query->fetchAll();

            $api = new ApiInterface();
            if ($api->checkApiKey($request->get('api_key'))) {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($result);
            } else {
                return new JsonResponse($api->apiKeyError(), 401);
            }
        }
    }

    /**
     * @Route("/get_comments")
     */
    public function getComments(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_comments (:lead)");
        $query->execute([":lead" => $request->get('leadId')]);
        $result = $query->fetchAll();

        $wynik = Array();

        foreach ($result as $r)
        {
            try {
                $login = $r["UserLogin"];
                $emOld = $this->getDoctrine()->getManager('olddb')->getConnection();
                $query2 = $emOld->prepare("CALL GetUserData (:login)");
                $query2->execute([":login" => $login]);
                $result2 = $query2->fetchAll();
                $r["commentUserName"] = $result2[0]["nazwisko"] . " " . $result2[0]["imie"]." (".$r["UserLogin"].")";
            }
            catch (Exception $e)
            {
                $r["commentUserName"] ="";
            }
            array_push($wynik, $r);
        }


        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')) )
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($wynik);
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }

    /**
     * @Route("/get_comment")
     */
    public function getComment(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_comment (:commentId)");
        $query->execute([":commentId" => $request->get('commentId')]);
        $result = $query->fetchAll();

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

    /**
     * @Route("/add_comment")
     */
    public function addComment(Request $request)
    {
        $req = $request->request->all();
        $req = $req["models"];
        $req = json_decode($req, true);

        foreach ($req as $key => $row) {

            $dateComment = date("Y-m-d h:i:s");

            $em = $this->getDoctrine()->getManager()->getConnection();
            $query = $em->prepare("CALL leads_add_comment (
                :comment_date_tmp, 
                :comment_tmp,
                :leads_id_tmp,
                :user_login_tmp
            )");
            $query->execute([
                ":comment_date_tmp" => $dateComment,
                ":comment_tmp" => $row["Comment"],
                ":leads_id_tmp" => $request->get('leadId'),
                ":user_login_tmp" => $request->get('user'),
            ]);

            $result = $query->fetchAll();

            $wynik = Array();

            foreach ($result as $r)
            {
                try {
                    $login = $r["UserLogin"];
                    $emOld = $this->getDoctrine()->getManager('olddb')->getConnection();
                    $query2 = $emOld->prepare("CALL GetUserData (:login)");
                    $query2->execute([":login" => $login]);
                    $result2 = $query2->fetchAll();
                    $r["commentUserName"] = $result2[0]["nazwisko"] . " " . $result2[0]["imie"]." (".$r["UserLogin"].")";
                }
                catch (Exception $e)
                {
                    $r["commentUserName"] ="";
                }
                array_push($wynik, $r);
            }

            $api = new ApiInterface();
            if ($api->checkApiKey($request->get('api_key'))) {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($wynik);
            } else {
                return new JsonResponse($api->apiKeyError(), 401);
            }
        }
    }

    /**
     * @Route("/edit_comment")
     */
    public function editComment(Request $request)
    {
        $req = $request->request->all();
        $req = $req["models"];
        $req = json_decode($req, true);

        foreach ($req as $key => $row) {

            $dateComment = date("Y-m-d", strtotime($row["CommentDate"] . ' UTC'));

            $em = $this->getDoctrine()->getManager()->getConnection();
            $query = $em->prepare("CALL leads_edit_comment (
                :id_tmp,
                :comment_date_tmp, 
                :comment_tmp,
                :leads_id_tmp,
                :user_login_tmp
            )");
            $query->execute([
                ":id_tmp" => $row["Id"],
                ":comment_date_tmp" => $row["CommentDate"],
                ":comment_tmp" => $row["Comment"],
                ":leads_id_tmp" => $request->get('leadId'),
                ":user_login_tmp" => $request->get('user'),
            ]);

            $result = $query->fetchAll();

            $api = new ApiInterface();
            if ($api->checkApiKey($request->get('api_key'))) {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($result);
            } else {
                return new JsonResponse($api->apiKeyError(), 401);
            }
        }
    }

    /**
     * @Route("/assign_lead/{id}/{agent}/{status}")
     */
    public function assignLead(Request $request)
    {

            $em = $this->getDoctrine()->getManager()->getConnection();
            $query = $em->prepare("CALL leads_assign (
                :agent_number_tmp, 
                :status_id_tmp, 
                :leads_id_tmp
            )");
            $query->execute([
                ":agent_number_tmp" => $request->get('agent'),
                ":status_id_tmp" => $request->get('status'),
                ":leads_id_tmp" => $request->get('id')
            ]);

            $result = $query->fetchAll();

            $api = new ApiInterface();
            if ($api->checkApiKey($request->get('api_key'))) {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($result);
            } else {
                return new JsonResponse($api->apiKeyError(), 401);
            }
    }

    /**
     * @Route("/get_history")
     */
    public function getHistory(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_history (:lead)");
        $query->execute([":lead" => $request->get('leadId')]);
        $result = $query->fetchAll();

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

    /**
     * @Route("/leads_structure_agents/{idAgent}")
     */
    public function getLeadsStructAgents(Request $request)
    {
        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $restAPI = new RestApiInterface();
            $restAPI->setUrl($this->getParameter('rest_api_agent_url').'Agent/'.$request->get('idAgent').'/DependentStructure');
            $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
            $restAPI->rawApiConnector();
            $json_table = $restAPI->getHttpResponse();

            //return new JsonResponse($json_table);

            $em = $this->getDoctrine()->getManager()->getConnection();
            $query = $em->prepare("CALL leads_get_leads_agent (:json_table)");
            $query->execute([":json_table" => $json_table]);
            $result = $query->fetchAll();

            if ($restAPI->getHttpCode() == 200)
            {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($result);
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
     * @Route("/add_geolocalization_comment")
     */
    public function addGeolocalizationComment(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_add_geolocalization_comment (
                :latitude_tmp, 
                :longitude_tmp
            )");
        $query->execute([
            ":latitude_tmp" => $request->get('latitude'),
            ":longitude_tmp" => $request->get('longitude'),
        ]);

        //$result = $query->fetchAll();

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL leads_get_comments (:lead)");
        $query->execute([":lead" => $request->get('leadId')]);
        $result = $query->fetchAll();

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
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