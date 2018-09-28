<?php

namespace AppBundle\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\ApiInterface;
use AppBundle\Utils\RestApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/bona", name="bona")
 */
class BonaController extends Controller
{

    /**
     * @Route("/setcontract", name="setcontract")
     */
    public function setContract(Request $request)
    {
        $msg = 'ERROR ';
        $api = new ApiInterface();
        if ($api->checkApiKey($request->get('api_key'))) {
            $em = $this->getDoctrine()->getManager()->getConnection();



            if($request->get('IncidentDate') != '')
            {
                $IncidentDate = date("Y-m-d H:i:s", strtotime($request->get('IncidentDate').' UTC'));
            }
            else
            {
                $IncidentDate = NULL;
            }

            if($request->get('NotificationDate') != '')
            {
                $NotificationDate = date("Y-m-d", strtotime($request->get('NotificationDate').' UTC'));
            }
            else
            {
                $NotificationDate = NULL;
            }

            if($request->get('OtherAgentContractDate') != '')
            {
                $OtherAgentContractDate = date("Y-m-d", strtotime($request->get('OtherAgentContractDate').' UTC'));
            }
            else
            {
                $OtherAgentContractDate = NULL;
            }

            if($request->get('TerminateDate') != '')
            {
                $TerminateDate = date("Y-m-d", strtotime($request->get('TerminateDate').' UTC'));
            }
            else
            {
                $TerminateDate = NULL;
            }


            $query = $em->prepare(
                "CALL bona_contract_add ( 
                    :agent_number, 
                    :unit_id,
                    :consultant_id,
                    :commision,
                    :VAT, 
                    :company,   
                    :forrwardingStreet,   
                    :forrwardingHomeNumber,   
                    :forrwardingPostalCode,   
                    :forrwardingCity,  
                    :incidentDate,
                    :reason,   
                    :description,  
                    :notification,
                    :notificationDate,
                    :paidOut,
                    :anountPaidOut,
                    :damageNumber,
                    :assignment,
                    :assignmentValue,
                    :otherAgent,
                    :otherAgentName,
                    :otherAgentContractDate,
                    :terminate,
                    :terminateDate,
                    :pageValue,
                    :consentSMS,
                    :consentEmail,   
                    :dataConsentDSA, 
                    :dataConsentPCRF, 
                    :dataConsentVOTUM, 
                    :dataConsentAUTOVOTUM, 
                    :dataConsentBEP, 
                    :marketingConsentDSA1, 
                    :marketingConsentDSA2, 
                    :marketingConsentVOTUM1, 
                    :marketingConsentVOTUM2,
                    :account_number,
                    :customer_firstname, 
                    :customer_lastname, 
                    :customer_street, 
                    :customer_streetnumber,
                    :customer_postcode, 
                    :customer_city,
                    :payment_form,
                    @ContractID
                    )"
            );

            try {
                $query->execute([
                    ":agent_number" => $request->get('AgentNumber'),
                    ":unit_id" => $request->get('UnitCode'),
                    ":consultant_id" => $request->get('ConsultantCode'),
                    ":commision" => intval($request->get('Commission')),
                    ":VAT" => intval($request->get('VAT')),
                    ":company" => intval($request->get('Company')),
                    ":forrwardingStreet" => $request->get('Street'),
                    ":forrwardingHomeNumber" => $request->get('HomeNumber'),
                    ":forrwardingPostalCode" => $request->get('PostalCode'),
                    ":forrwardingCity" => $request->get('City'),
                    ":incidentDate" => $IncidentDate,
                    ":reason" => $request->get('Reason'),
                    ":description" => $request->get('Discription'),
                    ":notification" => intval($request->get('Notification')),
                    ":notificationDate" => $NotificationDate,
                    ":paidOut" => intval($request->get('PaidOut')),
                    ":anountPaidOut" => $request->get('AnountPaidOut'),
                    ":damageNumber" => $request->get('DamageNumber'),
                    ":assignment" => intval($request->get('Assignment')),
                    ":assignmentValue" => $request->get('AssignmentValue'),
                    ":otherAgent" => intval($request->get('OtherAgent')),
                    ":otherAgentName" => $request->get('OtherAgentName'),
                    ":otherAgentContractDate" => $OtherAgentContractDate,
                    ":terminate" => intval($request->get('Terminate')),
                    ":terminateDate" => $TerminateDate,
                    ":pageValue" => intval($request->get('PageValue')),
                    ":consentSMS" => intval($request->get('ConsentSMS')),
                    ":consentEmail" => intval($request->get('ConsentEmail')),
                    ":dataConsentDSA" => intval($request->get('dataConsentDSA')),
                    ":dataConsentPCRF" => intval($request->get('dataConsentPCRF')),
                    ":dataConsentVOTUM" => intval($request->get('dataConsentVOTUM')),
                    ":dataConsentAUTOVOTUM" => intval($request->get('dataConsentAUTOVOTUM')),
                    ":dataConsentBEP" => intval($request->get('dataConsentBEP')),
                    ":marketingConsentDSA1" => intval($request->get('marketingConsentDSA1')),
                    ":marketingConsentDSA2" => intval($request->get('marketingConsentDSA2')),
                    ":marketingConsentVOTUM1" => intval($request->get('marketingConsentVOTUM1')),
                    ":marketingConsentVOTUM2" => intval($request->get('marketingConsentVOTUM2')),
                    ":account_number" => $request->get('AccountNumber'),
                    ":customer_firstname" => $request->get('CustomerFirstName'),
                    ":customer_lastname" => $request->get('CustomerLastName'),
                    ":customer_street" => $request->get('CustomerStreet'),
                    ":customer_streetnumber" => $request->get('CustomerHomeNumber'),
                    ":customer_postcode" => $request->get('CustomerPostCode'),
                    ":customer_city" => $request->get('CustomerCity'),
                    ":payment_form" => intval($request->get('PaymentForm'))
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE1:  ".$msg, 401);
            }
            $query->closeCursor();
            $contractID = $em->query("select @ContractID as `ContractID`")->fetch();

            $query = $em->prepare(
                "CALL person_add_as_company ( 
                        :agent_number_tmp, 
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
                        :PESEL, 
                        :IdNr, 
                        :NIP,
                        :REGON,
                        :KRS,
                        @ID )"
            );
            try {
                $query->execute([
                    ":agent_number_tmp" => $request->get('AgentNumber'),
                    ":first_name_tmp" => $request->get('FirstNameI'),
                    ":last_name_tmp" => $request->get('LastNameI'),
                    ":age_tmp" => 0,
                    ":street_tmp" => $request->get('StreetI'),
                    ":home_tmp" => $request->get('HomeNumberI'),
                    ":apartment_tmp" => $request->get('HomeNumberI'),
                    ":post_code_tmp" => $request->get('PostCodeI'),
                    ":post_office_tmp" => '',
                    ":city_tmp" => $request->get('CityI'),
                    ":victim_province_tmp" => '',
                    ":victim_district_tmp" => '',
                    ":victim_commune_tmp" => '',
                    ":phone_tmp" => $request->get('PhoneI'),
                    ":email_tmp" => $request->get('EmailI'),
                    ":PESEL" => $request->get('PESELI'),
                    ":IdNr" => $request->get('IdentityCardI'),
                    ":NIP" => $request->get('NIPI'),
                    ":REGON" => $request->get('REGONI'),
                    ":KRS" => $request->get('KRSI')
                ]);

            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE2:  ".$msg, 401);
            }
            $query->closeCursor();
            $personID = $em->query("select @ID as `id`")->fetch();

            $query = $em->prepare(
                "CALL person_add_as_company ( 
                        :agent_number_tmp, 
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
                        :PESEL, 
                        :IdNr, 
                        :NIP,
                        :REGON,
                        :KRS,
                        @ID )"
            );

            try {
                $query->execute([
                    ":agent_number_tmp" => $request->get('AgentNumber'),
                    ":first_name_tmp" => $request->get('FirstNameII'),
                    ":last_name_tmp" => $request->get('LastNameII'),
                    ":age_tmp" => 0,
                    ":street_tmp" => $request->get('StreetII'),
                    ":home_tmp" => $request->get('HomeNumberII'),
                    ":apartment_tmp" => $request->get('HomeNumberII'),
                    ":post_code_tmp" => $request->get('PostCodeII'),
                    ":post_office_tmp" => '',
                    ":city_tmp" => $request->get('CityII'),
                    ":victim_province_tmp" => '',
                    ":victim_district_tmp" => '',
                    ":victim_commune_tmp" => '',
                    ":phone_tmp" => $request->get('PhoneII'),
                    ":email_tmp" => $request->get('EmailII'),
                    ":PESEL" => $request->get('PESELII'),
                    ":IdNr" => $request->get('IdentityCardII'),
                    ":NIP" => '',
                    ":REGON" => '',
                    ":KRS" => ''
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE3:  ".$msg, 401);
            }
            $query->closeCursor();
            $secondPersonID = $em->query("select @ID as `id`")->fetch();

            $query = $em->prepare(
                "CALL person_add ( 
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
                        :PESEL, 
                        :IdNr, 
                        @ID )"
            );

            try {
                $query->execute([
                    ":first_name_tmp" => $request->get('PhoneFirstName'),
                    ":last_name_tmp" => $request->get('PhoneLastName'),
                    ":age_tmp" => 0,
                    ":street_tmp" => '',
                    ":home_tmp" => '',
                    ":apartment_tmp" => '',
                    ":post_code_tmp" => '',
                    ":post_office_tmp" => '',
                    ":city_tmp" => '',
                    ":victim_province_tmp" => '',
                    ":victim_district_tmp" => '',
                    ":victim_commune_tmp" => '',
                    ":phone_tmp" => '',
                    ":email_tmp" => '',
                    ":PESEL" => $request->get('PhonePESEL'),
                    ":IdNr" => '',
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE4:  ".$msg, 401);
            }
            $query->closeCursor();
            $phonePersonID = $em->query("Select @ID as `id`")->fetch();


            $query = $em->prepare("CALL bona_contract_person_add ( :bona_contract_person_contarct_id, :bona_contract_person_person_id )");
            try {
                $query->execute([
                    ":bona_contract_person_contarct_id" => $contractID["ContractID"],
                    ":bona_contract_person_person_id" => $personID["id"],
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE5:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bona_contract_person_add ( :bona_contract_person_contarct_id, :bona_contract_person_person_id )");
            try {
                $query->execute([
                    ":bona_contract_person_contarct_id" => $contractID["ContractID"],
                    ":bona_contract_person_person_id" => $secondPersonID["id"],
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE6:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bona_contract_person_add ( :bona_contract_person_contarct_id, :bona_contract_person_person_id )");
            try {
                $query->execute([
                    ":bona_contract_person_contarct_id" => $contractID["ContractID"],
                    ":bona_contract_person_person_id" => $phonePersonID["id"],
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE7:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bona_contract_policy_add ( :insurer_contract_id, :insurer_id, :policy_name, :policy_number )");
            try {
                $query->execute([
                    ":insurer_contract_id" => $contractID["ContractID"],
                    ":insurer_id" => intval($request->get('InsurerI')),
                    ":policy_name" => $request->get('PolicyNameI'),
                    ":policy_number" => $request->get('PolicyNumberI'),
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE8:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bona_contract_policy_add ( :insurer_contract_id, :insurer_id, :policy_name, :policy_number )");
            try {
                $query->execute([
                    ":insurer_contract_id" => $contractID["ContractID"],
                    ":insurer_id" => intval($request->get('InsurerII')),
                    ":policy_name" => $request->get('PolicyNameII'),
                    ":policy_number" => $request->get('PolicyNumberII'),
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE9:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bona_contract_policy_add ( :insurer_contract_id, :insurer_id, :policy_name, :policy_number )");
            try {
                $query->execute([
                    ":insurer_contract_id" => $contractID["ContractID"],
                    ":insurer_id" => intval($request->get('InsurerIII')),
                    ":policy_name" => $request->get('PolicyNameIII'),
                    ":policy_number" => $request->get('PolicyNumberIII'),
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE10:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bona_contract_questions_add ( :contract_id, :answer1, :answer2, :answer3, :answer4, :answer5, :answer6, :answer7, :answer8, :answer9)");
            try {
                $query->execute([
                    ":contract_id" => $contractID["ContractID"],
                    ":answer1" => $request->get('Answer1'),
                    ":answer2" => $request->get('Answer2'),
                    ":answer3" => $request->get('Answer3'),
                    ":answer4" => $request->get('Answer4'),
                    ":answer5" => $request->get('Answer5'),
                    ":answer6" => $request->get('Answer6'),
                    ":answer7" => $request->get('Answer7'),
                    ":answer8" => $request->get('Answer8'),
                    ":answer9" => $request->get('Answer9'),
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE10:  ".$msg, 401);
            }

            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($contractID);
        } else {
            return new JsonResponse($api->apiKeyError(), 401);
        }
    }

    /**
     * @Route("/getcontractlist/{agent_number}", name="getcontractlist")
     */
    public function getContractList(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL material_contract_getlist (:agent_number)");
        $query->execute([":agent_number" => $request->get('agent_number')]);
        $result = $query->fetchAll();

        foreach ($result as $key => $row) {
            $arr[] =
                array(
                    "ContractID" => $row["contractid"],
                    "ContractNumber" => $row["ContractNumber"],
                    "AddDate" => $row["add_date"],
                    "ContractName" => $row["ContractName"],
                    "ClientName" => $row["ClientName"],
                    "SentToCentral" => $row["sentToCentral"],
                );
        }
        $api = new ApiInterface();
        if ($api->checkApiKey($request->get('api_key'))) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($arr);
        } else {
            return new JsonResponse($api->apiKeyError(), 401);
        }
    }

    /**
     * @Route("/bonacontractget/{contractid}", name="bonacontractget")
     */
    public function getContract(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL bona_contract_get (:contractid)");
        $query->execute([":contractid" => $request->get('contractid')]);
        $result = $query->fetch();

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
     * @Route("/get_insurer")
     */
    public function getInsurer(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL insurer_get");
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
     * @Route("/editcontract", name="editcontract")
     */
    public function editContract(Request $request)
    {
        $msg = 'ERROR ';
        $api = new ApiInterface();
        if ($api->checkApiKey($request->get('api_key'))) {
            $em = $this->getDoctrine()->getManager()->getConnection();



            if($request->get('IncidentDate') != '')
            {
                $IncidentDate = date("Y-m-d H:i:s", strtotime($request->get('IncidentDate').' UTC'));
            }
            else
            {
                $IncidentDate = NULL;
            }

            if($request->get('NotificationDate') != '')
            {
                $NotificationDate = date("Y-m-d", strtotime($request->get('NotificationDate').' UTC'));
            }
            else
            {
                $NotificationDate = NULL;
            }

            if($request->get('OtherAgentContractDate') != '')
            {
                $OtherAgentContractDate = date("Y-m-d", strtotime($request->get('OtherAgentContractDate').' UTC'));
            }
            else
            {
                $OtherAgentContractDate = NULL;
            }

            if($request->get('TerminateDate') != '')
            {
                $TerminateDate = date("Y-m-d", strtotime($request->get('TerminateDate').' UTC'));
            }
            else
            {
                $TerminateDate = NULL;
            }


            $query = $em->prepare(
                "CALL bona_contract_edit ( 
                    :agent_number, 
                    :unit_id,
                    :consultant_id,
                    :commision,
                    :VAT, 
                    :company,   
                    :forrwardingStreet,   
                    :forrwardingHomeNumber,   
                    :forrwardingPostalCode,   
                    :forrwardingCity,  
                    :incidentDate,
                    :reason,   
                    :description,  
                    :notification,
                    :notificationDate,
                    :paidOut,
                    :anountPaidOut,
                    :damageNumber,
                    :assignment,
                    :assignmentValue,
                    :otherAgent,
                    :otherAgentName,
                    :otherAgentContractDate,
                    :terminate,
                    :terminateDate,
                    :pageValue,
                    :consentSMS,
                    :consentEmail,   
                    :dataConsentDSA, 
                    :dataConsentPCRF, 
                    :dataConsentVOTUM, 
                    :dataConsentAUTOVOTUM, 
                    :dataConsentBEP, 
                    :marketingConsentDSA1, 
                    :marketingConsentDSA2, 
                    :marketingConsentVOTUM1, 
                    :marketingConsentVOTUM2,
                    :account_number,
                    :customer_firstname, 
                    :customer_lastname, 
                    :customer_street, 
                    :customer_streetnumber,
                    :customer_postcode, 
                    :customer_city,
                    :payment_form,
                    :contract_id
                    )"
            );

            try {
                $query->execute([
                    ":agent_number" => $request->get('AgentNumber'),
                    ":unit_id" => $request->get('UnitCode'),
                    ":consultant_id" => $request->get('ConsultantCode'),
                    ":commision" => intval($request->get('Commission')),
                    ":VAT" => intval($request->get('VAT')),
                    ":company" => intval($request->get('Company')),
                    ":forrwardingStreet" => $request->get('Street'),
                    ":forrwardingHomeNumber" => $request->get('HomeNumber'),
                    ":forrwardingPostalCode" => $request->get('PostalCode'),
                    ":forrwardingCity" => $request->get('City'),
                    ":incidentDate" => $IncidentDate,
                    ":reason" => $request->get('Reason'),
                    ":description" => $request->get('Discription'),
                    ":notification" => intval($request->get('Notification')),
                    ":notificationDate" => $NotificationDate,
                    ":paidOut" => intval($request->get('PaidOut')),
                    ":anountPaidOut" => $request->get('AnountPaidOut'),
                    ":damageNumber" => $request->get('DamageNumber'),
                    ":assignment" => intval($request->get('Assignment')),
                    ":assignmentValue" => $request->get('AssignmentValue'),
                    ":otherAgent" => intval($request->get('OtherAgent')),
                    ":otherAgentName" => $request->get('OtherAgentName'),
                    ":otherAgentContractDate" => $OtherAgentContractDate,
                    ":terminate" => intval($request->get('Terminate')),
                    ":terminateDate" => $TerminateDate,
                    ":pageValue" => intval($request->get('PageValue')),
                    ":consentSMS" => intval($request->get('ConsentSMS')),
                    ":consentEmail" => intval($request->get('ConsentEmail')),
                    ":dataConsentDSA" => intval($request->get('dataConsentDSA')),
                    ":dataConsentPCRF" => intval($request->get('dataConsentPCRF')),
                    ":dataConsentVOTUM" => intval($request->get('dataConsentVOTUM')),
                    ":dataConsentAUTOVOTUM" => intval($request->get('dataConsentAUTOVOTUM')),
                    ":dataConsentBEP" => intval($request->get('dataConsentBEP')),
                    ":marketingConsentDSA1" => intval($request->get('marketingConsentDSA1')),
                    ":marketingConsentDSA2" => intval($request->get('marketingConsentDSA2')),
                    ":marketingConsentVOTUM1" => intval($request->get('marketingConsentVOTUM1')),
                    ":marketingConsentVOTUM2" => intval($request->get('marketingConsentVOTUM2')),
                    ":account_number" => $request->get('AccountNumber'),
                    ":customer_firstname" => $request->get('CustomerFirstName'),
                    ":customer_lastname" => $request->get('CustomerLastName'),
                    ":customer_street" => $request->get('CustomerStreet'),
                    ":customer_streetnumber" => $request->get('CustomerHomeNumber'),
                    ":customer_postcode" => $request->get('CustomerPostCode'),
                    ":customer_city" => $request->get('CustomerCity'),
                    ":payment_form" => intval($request->get('PaymentForm')),
                    ":contract_id" => $request->get('ContractID')
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE1:  ".$msg, 401);
            }
            //$query->closeCursor();
            //$contractID = $em->query("select @ContractID as `ContractID`")->fetch();

            $query = $em->prepare("CALL bona_contract_person_get ( :contract_id )");
            try {
                $query->execute([
                    ":contract_id" => $request->get('ContractID')
                ]);


            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE5:  ".$msg, 401);
            }

            $person = $query->fetch();
            //return new JsonResponse($person['person1']);

            //$query->closeCursor();
            //$Person = $em->query("SELECT person1 AS `PersonI`")->fetch();
            //$PersonII = $em->query("SELECT person2 AS `PersonII`")->fetch();
            //$PhonePerson = $em->query("SELECT phoneperson AS `PhonePerson`")->fetch();


            $query = $em->prepare(
                "CALL person_edit_as_company ( 
                        :agent_number_tmp, 
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
                        :PESEL, 
                        :IdNr, 
                        :NIP,
                        :REGON,
                        :KRS,
                        :person_id )"
            );
            try {
                $query->execute([
                    ":agent_number_tmp" => $request->get('AgentNumber'),
                    ":first_name_tmp" => $request->get('FirstNameI'),
                    ":last_name_tmp" => $request->get('LastNameI'),
                    ":age_tmp" => 0,
                    ":street_tmp" => $request->get('StreetI'),
                    ":home_tmp" => $request->get('HomeNumberI'),
                    ":apartment_tmp" => $request->get('HomeNumberI'),
                    ":post_code_tmp" => $request->get('PostCodeI'),
                    ":post_office_tmp" => '',
                    ":city_tmp" => $request->get('CityI'),
                    ":victim_province_tmp" => '',
                    ":victim_district_tmp" => '',
                    ":victim_commune_tmp" => '',
                    ":phone_tmp" => $request->get('PhoneI'),
                    ":email_tmp" => $request->get('EmailI'),
                    ":PESEL" => $request->get('PESELI'),
                    ":IdNr" => $request->get('IdentityCardI'),
                    ":NIP" => $request->get('NIPI'),
                    ":REGON" => $request->get('REGONI'),
                    ":KRS" => $request->get('KRSI'),
                    ":person_id" => $person['person1']
                ]);

            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE2:  ".$msg, 401);
            }
            //$query->closeCursor();
            //$personID = $em->query("select @ID as `id`")->fetch();

            $query = $em->prepare(
                "CALL person_edit_as_company ( 
                        :agent_number_tmp, 
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
                        :PESEL, 
                        :IdNr, 
                        :NIP,
                        :REGON,
                        :KRS,
                        :person_id )"
            );

            try {
                $query->execute([
                    ":agent_number_tmp" => $request->get('AgentNumber'),
                    ":first_name_tmp" => $request->get('FirstNameII'),
                    ":last_name_tmp" => $request->get('LastNameII'),
                    ":age_tmp" => 0,
                    ":street_tmp" => $request->get('StreetII'),
                    ":home_tmp" => $request->get('HomeNumberII'),
                    ":apartment_tmp" => $request->get('HomeNumberII'),
                    ":post_code_tmp" => $request->get('PostCodeII'),
                    ":post_office_tmp" => '',
                    ":city_tmp" => $request->get('CityII'),
                    ":victim_province_tmp" => '',
                    ":victim_district_tmp" => '',
                    ":victim_commune_tmp" => '',
                    ":phone_tmp" => $request->get('PhoneII'),
                    ":email_tmp" => $request->get('EmailII'),
                    ":PESEL" => $request->get('PESELII'),
                    ":IdNr" => $request->get('IdentityCardII'),
                    ":NIP" => '',
                    ":REGON" => '',
                    ":KRS" => '',
                    ":person_id" => $person['person2']
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE3:  ".$msg, 401);
            }
            //$query->closeCursor();
            //$secondPersonID = $em->query("select @ID as `id`")->fetch();

            $query = $em->prepare(
                "CALL person_edit ( 
                        :person_id,
                        :first_name_tmp, 
                        :last_name_tmp, 
                        :street_tmp, 
                        :home_tmp,
                        :apartment_tmp, 
                        :post_code_tmp, 
                        :post_office_tmp,  
                        :city_tmp, 
                        :phone_tmp, 
                        :email_tmp,  
                        :PESEL, 
                        :IdNr )"
            );

            try {
                $query->execute([
                    "person_id" => $person['phoneperson'],
                    ":first_name_tmp" => $request->get('PhoneFirstName'),
                    ":last_name_tmp" => $request->get('PhoneLastName'),
                    ":street_tmp" => '',
                    ":home_tmp" => '',
                    ":apartment_tmp" => '',
                    ":post_code_tmp" => '',
                    ":post_office_tmp" => '',
                    ":city_tmp" => '',
                    ":phone_tmp" => '',
                    ":email_tmp" => '',
                    ":PESEL" => $request->get('PhonePESEL'),
                    ":IdNr" => '',
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE4:  ".$msg, 401);
            }
            //$query->closeCursor();
            //$phonePersonID = $em->query("Select @ID as `id`")->fetch();


            /*$query = $em->prepare("CALL bona_contract_person_add ( :bona_contract_person_contarct_id, :bona_contract_person_person_id )");
            try {
                $query->execute([
                    ":bona_contract_person_contarct_id" => $contractID["ContractID"],
                    ":bona_contract_person_person_id" => $personID["id"],
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE5:  ".$msg, 401);
            }*/

            /*$query = $em->prepare("CALL bona_contract_person_add ( :bona_contract_person_contarct_id, :bona_contract_person_person_id )");
            try {
                $query->execute([
                    ":bona_contract_person_contarct_id" => $contractID["ContractID"],
                    ":bona_contract_person_person_id" => $secondPersonID["id"],
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE6:  ".$msg, 401);
            }*/

            /*$query = $em->prepare("CALL bona_contract_person_add ( :bona_contract_person_contarct_id, :bona_contract_person_person_id )");
            try {
                $query->execute([
                    ":bona_contract_person_contarct_id" => $contractID["ContractID"],
                    ":bona_contract_person_person_id" => $phonePersonID["id"],
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE7:  ".$msg, 401);
            }*/

            $query = $em->prepare("CALL bona_contract_policy_edit ( :contract_id, :insurer_id, :policy_name, :policy_number, :ordinary )");
            try {
                $query->execute([
                    ":contract_id" => $request->get('ContractID'),
                    ":insurer_id" => intval($request->get('InsurerI')),
                    ":policy_name" => $request->get('PolicyNameI'),
                    ":policy_number" => $request->get('PolicyNumberI'),
                    ":ordinary" => 1,
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE8:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bona_contract_policy_edit ( :contract_id, :insurer_id, :policy_name, :policy_number, :ordinary )");
            try {
                $query->execute([
                    ":contract_id" => $request->get('ContractID'),
                    ":insurer_id" => intval($request->get('InsurerII')),
                    ":policy_name" => $request->get('PolicyNameII'),
                    ":policy_number" => $request->get('PolicyNumberII'),
                    ":ordinary" => 2,
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE9:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bona_contract_policy_edit ( :contract_id, :insurer_id, :policy_name, :policy_number, :ordinary )");
            try {
                $query->execute([
                    ":contract_id" => $request->get('ContractID'),
                    ":insurer_id" => intval($request->get('InsurerIII')),
                    ":policy_name" => $request->get('PolicyNameIII'),
                    ":policy_number" => $request->get('PolicyNumberIII'),
                    ":ordinary" => 3,
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE10:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bona_contract_questions_edit ( :contract_id, :answer1, :answer2, :answer3, :answer4, :answer5, :answer6, :answer7, :answer8, :answer9)");
            try {
                $query->execute([
                    ":contract_id" => $request->get('ContractID'),
                    ":answer1" => $request->get('Answer1'),
                    ":answer2" => $request->get('Answer2'),
                    ":answer3" => $request->get('Answer3'),
                    ":answer4" => $request->get('Answer4'),
                    ":answer5" => $request->get('Answer5'),
                    ":answer6" => $request->get('Answer6'),
                    ":answer7" => $request->get('Answer7'),
                    ":answer8" => $request->get('Answer8'),
                    ":answer9" => $request->get('Answer9'),
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE10:  ".$msg, 401);
            }

            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($request->get('ContractID'));
        } else {
            return new JsonResponse($api->apiKeyError(), 401);
        }
    }
}
