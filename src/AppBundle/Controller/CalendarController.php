<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\ApiInterface;
use AppBundle\Utils\RestApiInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/calendar", name="calendar")
 */

class CalendarController extends Controller
{
    /**
     * @Route("/get/{id}")
     */
    public function getEvents(Request $request)
    {
        $em = $this->getDoctrine()->getManager('database')->getConnection();
        $query = $em->prepare("CALL calendar_get_events (:uid)");
        $query->execute([":uid" => ApiInterface::decryptUrlValue($request->get('id'))]);
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
     * @Route("/update/{id}")
     */
    public function updateEvent(Request $request)
    {
        $result = $request->request->all();
        $result = $result["models"];
        $result = json_decode($result, true);

        foreach ($result as $key => $row) {
            if ($row["event_id"] > 0) {
                $sInLocal = date("Y-m-d H:i:s", strtotime($row["start_date"] . ' UTC'));
                $eInLocal = date("Y-m-d H:i:s", strtotime($row["end_date"] . ' UTC'));

                $em = $this->getDoctrine()->getManager('database')->getConnection();
                $query = $em->prepare("CALL calendar_update_event (:event_id, :title, :description, :start_date, :end_date, :allday)");
                $query->execute([":event_id" => $row["event_id"],
                                 ":title" => $row["title"],
                                 ":description" => $row["description"],
                                 ":start_date" => $sInLocal,
                                 ":end_date" => $eInLocal,
                                 ":allday" => $row["allDay"]
                                ]);
            }
        }

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
     * @Route("/add/{id}")
     */
    public function addEvent(Request $request)
    {
        $result = $request->request->all();
        $result = $result["models"];
        $result = json_decode($result, true);
        $row = $result[0];

        if ($row) {
                $sInLocal = date("Y-m-d H:i:s", strtotime($row["start_date"] . ' UTC'));
                $eInLocal = date("Y-m-d H:i:s", strtotime($row["end_date"] . ' UTC'));

                $em = $this->getDoctrine()->getManager('database')->getConnection();
                $query = $em->prepare("CALL calendar_add_event (
                                                                :user_id,
                                                                :title, 
                                                                :start_date, 
                                                                :end_date,
                                                                :allday,
                                                                :is_deleted,
                                                                :description,
                                                                @LID
                                                                )"
                                                        );
                $query->execute([
                    ":user_id" =>  $row["user_id"],
                    ":title" => $row["title"],
                    ":start_date" => $sInLocal,
                    ":end_date" => $eInLocal,
                    ":allday" => 0,
                    ":is_deleted" => 0,
                    ":description" => $row["description"]
                ]);
                $query->closeCursor();
                $out = $em->query("select @LID")->fetch();
        }


        $result[0]["event_id"] = $out["@LID"];

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
     * @Route("/delete")
     */
    public function deleteEvent(Request $request)
    {
        $result = $request->request->all();
        $result = $result["models"];
        $result = json_decode($result, true);
        $row = $result[0];

        if ($row["event_id"] > 0) {
                 $em = $this->getDoctrine()->getManager('database')->getConnection();
                $query = $em->prepare("CALL calendar_delete_event (:event_id)");
                $query->execute([":event_id" => $row["event_id"]]);
        }


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