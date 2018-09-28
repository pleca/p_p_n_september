<?php
/**
 * Created by PhpStorm.
 * User: mmedynski
 * Date: 18.09.2018
 * Time: 10:19
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\ApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/transferbls", name="transferbls")
 */

class TransferBlsController extends Controller
{
    /**
     * @Route("/setcontract", name="setcontract")
     */
    public function setContract(Request $request)
    {
        $msg = 'ERROR ';
        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $em = $this->getDoctrine()->getManager()->getConnection();

            if($request->get('IncidentDate') != '')
            {
                $IncidentDate = date("Y-m-d H:i:s", strtotime($request->get('IncidentDate').' UTC'));
            }
            else
            {
                $IncidentDate = NULL;
            }

            $query = $em->prepare(
                "CALL transfer_bls_contract_add ( 
                    :agent_number, 
                    :unit_id,
                    :consultant_id,                
                    :incidentDate,
                    @ContractID
                    )"
            );

            try {
                $query->execute([
                    ":agent_number" => $request->get('AgentNumber'),
                    ":unit_id" => $request->get('UnitId'),
                    ":consultant_id" => $request->get('ConsultantId'),
                    ":incidentDate" => $IncidentDate
                ]);

                /*$query->execute([
                    ":agent_number" => 'mmedynski',
                    ":unit_id" => '1',
                    ":consultant_id" => '2',
                    ":incidentDate" => '2018-01-01'
                ]);*/
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE1:  ".$msg, 401);
            }

            $query->closeCursor();
            $contractID = $em->query("select @ContractID as `ContractID`")->fetch();

            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($contractID);

        }
        else
        {
            return new JsonResponse($api->apiKeyError(), 401);
        }
    }

    /**
     * @Route("/get_tax_office")
     */
    public function getTaxOffice(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL tax_office_get");
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
}