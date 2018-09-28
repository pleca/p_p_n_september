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
 * @Route("/frank", name="frank")
 */
class FrankController extends Controller
{

    /**
     * @Route("/setcontract", name="setcontract")
     */
    public function setContract(Request $request)
    {
        $msg = '';
        $api = new ApiInterface();
        if ($api->checkApiKey($request->get('api_key'))) {
            $em = $this->getDoctrine()->getManager()->getConnection();

            $query = $em->prepare("CALL bank_contract_add ( :agent_number, :unit_id, :consultant_id, 
                 :RadioButton1, :RadioButton2, :RadioButton3, :CheckBox1, :CheckBox2, :CheckBox3, 
                 :CheckBox1Date, :CheckBox2Date, :CheckBox3Date, :account_number,
                 :customer_firstname, :customer_lastname, :customer_street, :customer_streetnumber, :customer_apartment,
                 :customer_postcode, :customer_city, :commission_id, :SubjectID, :AgreementAdditionalDate, 
                 :dataConsentDSA, :dataConsentPCRF, :dataConsentVOTUM, :dataConsentAUTOVOTUM, :dataConsentBEP, 
                 :marketingConsentDSA1, :marketingConsentDSA2, :marketingConsentVOTUM1, :marketingConsentVOTUM2, 
                 :bankId, :bankContractNumber, :contractType, :forrwardingStreet, :forrwardingHomeNumber, :forrwardingApartmentNumber, :forrwardingPostalCode ,
                 :forrwardingCity, :other_agent_name, :other_agent_date, :creditType, :mandateBankName, :bankContractDate, :unit_number, :consultant_number,               
                 @ID )");
            try {
                $query->execute([
                    ":agent_number" => $request->get('AgentNumber'),
                    ":unit_id" => $request->get('Unit'),
                    ":consultant_id" =>  $request->get('Consultant'),
                    ":RadioButton1" => intval($request->get('RadioButton1')),
                    ":RadioButton2" => intval($request->get('RadioButton2')),
                    ":RadioButton3" => intval($request->get('RadioButton3')),
                    ":CheckBox1" => intval($request->get('CheckBox1')),
                    ":CheckBox2" => intval($request->get('CheckBox2')),
                    ":CheckBox3" => intval($request->get('CheckBox3')),
                    ":CheckBox1Date" => $request->get('CheckBox1Date'),
                    ":CheckBox2Date" => $request->get('CheckBox2Date'),
                    ":CheckBox3Date" => $request->get('CheckBox3Date'),
                    ":account_number" => $request->get('AccountNumber'),
                    ":customer_firstname" => $request->get('CustomerFirstName'),
                    ":customer_lastname" => $request->get('CustomerLastName'),
                    ":customer_street" => $request->get('CustomerStreet'),
                    ":customer_streetnumber" => $request->get('CustomerHomeNumber'),
                    ":customer_apartment" => $request->get('CustomerApartment'),
                    ":customer_postcode" => $request->get('CustomerPostCode'),
                    ":customer_city" => $request->get('CustomerCity'),
                    ":commission_id" => intval($request->get('commission_id')),
                    ":SubjectID" => $request->get('SubjectID'),
                    ":AgreementAdditionalDate" => intval($request->get('AgreementAdditionalDate')),
                    ":dataConsentDSA" => intval($request->get('dataConsentDSA')),
                    ":dataConsentPCRF" => intval($request->get('dataConsentPCRF')),
                    ":dataConsentVOTUM" => intval($request->get('dataConsentVOTUM')),
                    ":dataConsentAUTOVOTUM" => intval($request->get('dataConsentAUTOVOTUM')),
                    ":dataConsentBEP" => intval($request->get('dataConsentBEP')),
                    ":marketingConsentDSA1" => intval($request->get('marketingConsentDSA1')),
                    ":marketingConsentDSA2" => intval($request->get('marketingConsentDSA2')),
                    ":marketingConsentVOTUM1" => intval($request->get('marketingConsentVOTUM1')),
                    ":marketingConsentVOTUM2" => intval($request->get('marketingConsentVOTUM2')),
                    ":bankId" => intval($request->get('BankName')),
                    ":bankContractNumber" => $request->get('ContractNumber'),
                    ":contractType" => intval($request->get('ContractType')),
                    ":forrwardingStreet" => $request->get('Street'),
                    ":forrwardingHomeNumber" => $request->get('HomeNumber'),
                    ":forrwardingApartmentNumber" => $request->get('ApartmentNumber'),
                    ":forrwardingPostalCode" => $request->get('PostCode'),
                    ":forrwardingCity" => $request->get('City'),
                    ":other_agent_name" => $request->get('OtherAgentName'),
                    ":other_agent_date" => $request->get('OtherAgentDate'),
                    ":creditType" => intval($request->get('CreditType')),
                    ":mandateBankName" => intval($request->get('MandateBankName')),
                    ":bankContractDate" => $request->get('BankContractDate'),
                    ":unit_number" => $request->get('UnitNumber'),
                    ":consultant_number" =>  $request->get('ConsultantNumber'),
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();
                return new JsonResponse("MESSAGE1:  ".$msg, 401);
            }
            //
            $query->closeCursor();
            $contractID = $em->query("select @ID as `ContractID`")->fetch();
            $query = $em->prepare("CALL person_add ( :first_name_tmp, :last_name_tmp, :age_tmp, :street_tmp, :home_tmp,
                            :apartment_tmp, :post_code_tmp, :post_office_tmp,  :city_tmp, :victim_province_tmp, :victim_district_tmp,
                            :victim_commune_tmp, :phone_tmp, :email_tmp, :PESEL, :IdNr, @ID )");
            try {
                $query->execute([
                    ":first_name_tmp" => $request->get('FirstNameI'),
                    ":last_name_tmp" => $request->get('LastNameI'),
                    ":age_tmp" => 0,
                    ":street_tmp" => $request->get('StreetI'),
                    ":home_tmp" => $request->get('HomeNumberI'),
                    ":apartment_tmp" => $request->get('ApartmentNumberI'),
                    ":post_code_tmp" => $request->get('PostCodeI'),
                    ":post_office_tmp" => '',
                    ":city_tmp" => $request->get('CityI'),
                    ":victim_province_tmp" => "",
                    ":victim_district_tmp" => '',
                    ":victim_commune_tmp" => '',
                    ":phone_tmp" => $request->get('PhoneI'),
                    ":email_tmp" => $request->get('EmailI'),
                    ":PESEL" => $request->get('PESELI'),
                    ":IdNr" => $request->get('IdentityCardI'),
                ]);

            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE2:  ".$msg, 401);
            }
            $query->closeCursor();
            $personID = $em->query("select @ID as `id`")->fetch();

            $query = $em->prepare("CALL person_add ( :first_name_tmp, :last_name_tmp, :age_tmp, :street_tmp, :home_tmp,
                            :apartment_tmp, :post_code_tmp, :post_office_tmp,  :city_tmp, :victim_province_tmp, :victim_district_tmp,
                            :victim_commune_tmp, :phone_tmp, :email_tmp,  :PESEL, :IdNr, @ID )");

            try {
                $query->execute([
                    ":first_name_tmp" => $request->get('FirstNameII'),
                    ":last_name_tmp" => $request->get('LastNameII'),
                    ":age_tmp" => 0,
                    ":street_tmp" => $request->get('StreetII'),
                    ":home_tmp" => $request->get('HomeNumberII'),
                    ":apartment_tmp" => $request->get('ApartmentNumberII'),
                    ":post_code_tmp" => $request->get('PostCodeII'),
                    ":post_office_tmp" => '',
                    ":city_tmp" => $request->get('CityII'),
                    ":victim_province_tmp" => "",
                    ":victim_district_tmp" => '',
                    ":victim_commune_tmp" => '',
                    ":phone_tmp" => $request->get('PhoneII'),
                    ":email_tmp" => $request->get('EmailII'),
                    ":PESEL" => $request->get('PESELII'),
                    ":IdNr" => $request->get('IdentityCardII'),
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE3:  ".$msg, 401);
            }
            $query->closeCursor();
            $secondPersonID = $em->query("select @ID as `id`")->fetch();

            $query = $em->prepare("CALL bank_contract_person_add ( :bank_contract_person_contarct_id, :bank_contract_person_person_id )");

            try {
                $query->execute([
                    ":bank_contract_person_contarct_id" => $contractID["ContractID"],
                    ":bank_contract_person_person_id" => $personID["id"],
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE4:  ".$msg, 401);
            }

            $query = $em->prepare("CALL bank_contract_person_add ( :bank_contract_person_contarct_id, :bank_contract_person_person_id )");
            try {
                $query->execute([
                    ":bank_contract_person_contarct_id" => $contractID["ContractID"],
                    ":bank_contract_person_person_id" => $secondPersonID["id"],
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE5:  ".$msg, 401);
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
        $query = $em->prepare("CALL bank_contract_getlist (:agent_number)");
        $query->execute([":agent_number" => $request->get('agent_number')]);
        $result = $query->fetchAll();

        foreach ($result as $key => $row) {
            $arr[] =
                array(
                    "ContarctID" => intval($row["contractid"]),
                    "AddDate" => $row["add_date"],
                    "ConsultantID" => $row["consultant_id"],
                    "LastName" => $row["LastName"],
                    "FirstName" => $row["FirstName"],
                    "sentToCentral" => $row["sentToCentral"],
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
     * @Route("/getcontractcommission", name="getcontractcommission")
     */
    public function getContractCommission(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL bank_contract_getcommission ()");
        $query->execute();
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
        } else {
            $returnData = array("error" => "null data returned");

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
        } else {
            $returnData = array("error" => "null data returned");

            return new Response(json_encode($returnData, true), 404);
        }

    }

    /**
     * @Route("/updatecontract", name="updatecontract")
     */
    public function updateContract(Request $request)
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();
        $msg = '';
        $api = new ApiInterface();
        if ($api->checkApiKey($request->get('api_key'))) {
            $em = $this->getDoctrine()->getManager()->getConnection();

            $query = $em->prepare("CALL bank_contract_edit ( :agent_number, :unit_id, :consultant_id, 
                 :RadioButton1, :RadioButton2, :RadioButton3, :CheckBox1, :CheckBox2, :CheckBox3, 
                 :CheckBox1Date, :CheckBox2Date, :CheckBox3Date, :account_number,
                 :customer_firstname, :customer_lastname, :customer_street, :customer_streetnumber, :customer_apartmentnumber,
                 :customer_postcode, :customer_city, :dataConsentDSA, :dataConsentPCRF, :dataConsentVOTUM, :dataConsentAUTOVOTUM, 
                 :dataConsentBEP, :marketingConsentDSA1, :marketingConsentDSA2, :marketingConsentVOTUM1, :marketingConsentVOTUM2, 
                 :other_agent_name, :other_agent_date, :bank_contract_date, :unit_number, :consultant_number, :contract_id )");

            try {
                $query->execute([
                    ":agent_number" => $request->get('AgentNumber'),
                    ":unit_id" => $request->get('Unit'),
                    ":consultant_id" => $request->get('Consultant'),
                    ":RadioButton1" => intval($request->get('RadioButton1')),
                    ":RadioButton2" => intval($request->get('RadioButton2')),
                    ":RadioButton3" => intval($request->get('RadioButton3')),
                    ":CheckBox1" => intval($request->get('CheckBox1')),
                    ":CheckBox2" => intval($request->get('CheckBox2')),
                    ":CheckBox3" => intval($request->get('CheckBox3')),
                    ":CheckBox1Date" => $request->get('CheckBox1Date'),
                    ":CheckBox2Date" => $request->get('CheckBox2Date'),
                    ":CheckBox3Date" => $request->get('CheckBox3Date'),
                    ":account_number" => $request->get('AccountNumber'),
                    ":customer_firstname" => $request->get('CustomerFirstName'),
                    ":customer_lastname" => $request->get('CustomerLastName'),
                    ":customer_street" => $request->get('CustomerStreet'),
                    ":customer_streetnumber" => $request->get('CustomerHomeNumber'),
                    ":customer_apartmentnumber" => $request->get('CustomerApartmentNumber'),
                    ":customer_postcode" => $request->get('CustomerPostCode'),
                    ":customer_city" => $request->get('CustomerCity'),
                    ":dataConsentDSA" => intval($request->get('dataConsentDSA')),
                    ":dataConsentPCRF" => intval($request->get('dataConsentPCRF')),
                    ":dataConsentVOTUM" => intval($request->get('dataConsentVOTUM')),
                    ":dataConsentAUTOVOTUM" => intval($request->get('dataConsentAUTOVOTUM')),
                    ":dataConsentBEP" => intval($request->get('dataConsentBEP')),
                    ":marketingConsentDSA1" => intval($request->get('marketingConsentDSA1')),
                    ":marketingConsentDSA2" => intval($request->get('marketingConsentDSA2')),
                    ":marketingConsentVOTUM1" => intval($request->get('marketingConsentVOTUM1')),
                    ":marketingConsentVOTUM2" => intval($request->get('marketingConsentVOTUM2')),
                    ":other_agent_name" => $request->get('OtherAgentName'),
                    ":other_agent_date" => $request->get('OtherAgentDate'),
                    ":bank_contract_date" => $request->get('BankContractDate'),
                    ":unit_number" => $request->get('UnitNumber'),
                    ":consultant_number" => $request->get('ConsultantNumber'),
                    ":contract_id" => $request->get('ContractID'),
                ]);
                if ($query) {
                    $msg .= 'query1 OK, ';
                }
            } catch (\Exception $e) {
                $msg = ":agent_number: [".$request->get('AgentNumber').']; ';
                $msg .= ":unit_id: [".$request->get('Unit').']; ';
                $msg .= ":consultant_id: [".$request->get('Consultant').']; ';
                $msg .= ":RadioButton1: [".intval($request->get('RadioButton1')).']; ';
                $msg .= ":RadioButton2: [".intval($request->get('RadioButton2')).']; ';
                $msg .= ":RadioButton3: [".intval($request->get('RadioButton3')).']; ';
                $msg .= ":CheckBox1: [".intval($request->get('CheckBox1')).']; ';
                $msg .= ":CheckBox2: [".intval($request->get('CheckBox2')).']; ';
                $msg .= ":CheckBox3: [".intval($request->get('CheckBox3')).']; ';
                $msg .= ":CheckBox1Date: [".$request->get('CheckBox1').']; ';
                $msg .= ":CheckBox2Date: [".$request->get('CheckBox2').']; ';
                $msg .= ":CheckBox3Date: [".$request->get('CheckBox3').']; ';
                $msg .= ":account_number: [".$request->get('AccountNumber').']; ';
                $msg .= ":customer_firstname: [".$request->get('CustomerFirstName').']; ';
                $msg .= ":customer_lastname: [".$request->get('CustomerLastName').']; ';
                $msg .= ":customer_street: [".$request->get('CustomerStreet').']; ';
                $msg .= ":customer_streetnumber: [".$request->get('CustomerHomeNumber').']; ';
                $msg .= ":customer_apartmentnumber: [".$request->get('CustomerApartmentNumber').']; ';
                $msg .= ":customer_postcode: [".$request->get('CustomerPostCode').']; ';
                $msg .= ":customer_city: [".$request->get('CustomerCity').']; ';
                $msg .= ":dataConsentDSA: [".intval($request->get('dataConsentDSA')).']; ';
                $msg .= ":dataConsentPCRF: [".intval($request->get('dataConsentPCRF')).']; ';
                $msg .= ":dataConsentVOTUM: [".intval($request->get('dataConsentVOTUM')).']; ';
                $msg .= ":dataConsentAUTOVOTUM: [".intval($request->get('dataConsentAUTOVOTUM')).']; ';
                $msg .= ":dataConsentBEP: [".intval($request->get('dataConsentBEP')).']; ';
                $msg .= ":marketingConsentDSA1: [".intval($request->get('marketingConsentDSA1')).']; ';
                $msg .= ":marketingConsentDSA2: [".intval($request->get('marketingConsentDSA2')).']; ';
                $msg .= ":marketingConsentVOTUM1: [".intval($request->get('marketingConsentVOTUM1')).']; ';
                $msg .= ":marketingConsentVOTUM2 [".intval($request->get('marketingConsentVOTUM2')).']; ';
                $msg .= ":other_agent_name: [".$request->get('other_agent_name').']; ';
                $msg .= ":other_agent_date".$request->get('other_agent_date').'; ';
                $msg .= ":bank_contract_date".$request->get('bank_contract_date').'; ';
                $msg .= ":unit_number: [".$request->get('UnitNumber').']; ';
                $msg .= ":consultant_number: [".$request->get('ConsultantNumber').']; ';
                $msg .= ":contract_id: [".$request->get('ContractID').']; ';

                $msg .= $e->getMessage();

                return new JsonResponse("MESSAGE1:  ".$msg, 401);
            }

            $em = $this->getDoctrine()->getManager()->getConnection();
            $query = $em->prepare("CALL bank_contract_get_committed_persons ( :ContractId )");

            try {
                $query->execute([":ContractId" => $request->get('ContractID')]);
                if ($query) {
                    $msg .= 'query2 OK, ';
                }
            } catch (\Exception $e) {
                $msg = $e->getMessage();

                return new JsonResponse("MESSAGE2:  ".$msg, 401);
            }

            $getPerson = $query->fetchAll();
            $jp = json_encode($getPerson);
            $msg .= 'fetchAll: '.$jp.' OK, ';

            $i = 1;

            $query = $em->prepare("CALL person_edit ( :person_id, :first_name_tmp, :last_name_tmp, :street_tmp, :home_tmp,
                                :apartment_tmp, :post_code_tmp, :post_office_tmp,  :city_tmp, :phone_tmp, :email_tmp, :PESEL, 
                                :IdNr)");

            try {
                $query->execute([
                    ":person_id" => intval($getPerson[0]['bank_contract_person_person_id']),
                    ":first_name_tmp" => $request->get('FirstNameI'),
                    ":last_name_tmp" => $request->get('LastNameI'),
                    ":street_tmp" => $request->get('StreetI'),
                    ":home_tmp" => $request->get('HomeNumberI'),
                    ":apartment_tmp" => $request->get('ApartmentNumberI'),
                    ":post_code_tmp" => $request->get('PostCodeI'),
                    ":post_office_tmp" => '',
                    ":city_tmp" => $request->get('CityI'),
                    ":phone_tmp" => $request->get('PhoneI'),
                    ":email_tmp" => $request->get('EmailI'),
                    ":PESEL" => $request->get('PESELI'),
                    ":IdNr" => $request->get('IdentityCardI'),
                ]);
                if ($query) {
                    $msg .= 'query3.'.$i.' OK, ';
                } else {
                    $msg .= 'query3.'.$i.' error, ';
                }
            } catch (\Exception $e) {
                $msg = "MESSAGE3:  ".$e->getMessage();

                return new JsonResponse($msg, 401);
            }

            $query = $em->prepare("CALL person_edit ( :person_id, :first_name_tmp, :last_name_tmp, :street_tmp, :home_tmp,
                                :apartment_tmp, :post_code_tmp, :post_office_tmp,  :city_tmp, :phone_tmp, :email_tmp, :PESEL, 
                                :IdNr)");
            try {
                $query->execute([
                    ":person_id" => intval($getPerson[1]['bank_contract_person_person_id']),
                    ":first_name_tmp" => $request->get('FirstNameII'),
                    ":last_name_tmp" => $request->get('LastNameII'),
                    ":street_tmp" => $request->get('StreetII'),
                    ":home_tmp" => $request->get('HomeNumberII'),
                    ":apartment_tmp" => $request->get('ApartmentNumberII'),
                    ":post_code_tmp" => $request->get('PostCodeII'),
                    ":post_office_tmp" => '',
                    ":city_tmp" => $request->get('CityII'),
                    ":phone_tmp" => $request->get('PhoneII'),
                    ":email_tmp" => $request->get('EmailII'),
                    ":PESEL" => $request->get('PESELII'),
                    ":IdNr" => $request->get('IdentityCardII'),
                ]);
                if ($query) {
                    $msg .= 'query4.'.$i.' OK, ';
                } else {
                    $msg .= 'query4.'.$i.' error, ';
                }
            } catch (\Exception $e) {
                $msg = "MESSAGE3:  ".$e->getMessage();

                return new JsonResponse($msg, 401);
            }

            $query = $em->prepare("CALL forwarding_adress_edit ( :contract_id, :street_tmp, :home_tmp,
                                :apartment_tmp, :post_code_tmp, :post_office_tmp,  :city_tmp)");

            try {
                $query->execute([
                    ":contract_id" => intval($request->get('ContractID')),
                    ":street_tmp" => $request->get('Street'),
                    ":home_tmp" => $request->get('HomeNumber'),
                    ":apartment_tmp" => $request->get('ApartmentNumber'),
                    ":post_code_tmp" => $request->get('PostCode'),
                    ":post_office_tmp" => '',
                    ":city_tmp" => $request->get('City'),
                ]);
                if ($query) {
                    $msg .= 'query5.'.$i.' OK, ';
                    $msg .= ' ContractID: '.intval($request->get('ContractID'));
                    $msg .= ' Street: '.$request->get('Street');
                    $msg .= ' HomeNumber: '.$request->get('HomeNumber');
                    $msg .= ' ApartmentNumber: '.$request->get('ApartmentNumber');
                    $msg .= ' PostCode: '.$request->get('PostCode');
                    $msg .= '  ';
                    $msg .= ' City: '.$request->get('City');
                } else {
                    $msg .= 'query5.'.$i.' error, ';
                }
            } catch (\Exception $e) {
                $msg = "MESSAGE3:  ".$e->getMessage();

                return new JsonResponse($msg, 401);
            }

            $status = array('status' => "success", "updated" => true);

            return new JsonResponse(intval($request->get('ContractID')), 201);
        } else {
            return new JsonResponse($api->apiKeyError(), 401);
        }
    }

	
	/**
     * @Route("/sendtocentral", name="sendtocentral")
     */
    public function sendToCentral(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL get_bank_contract_data_for_central (:contractId)");
        $query->execute([":contractId" => $request->get('contractId')]);
        $result = $query->fetch();
        $result['ContractDate'] = date("d.m.Y", strtotime($result['ContractDate']));
        $contractOut = new RestApiInterface();
        $contractOut->setUrl($this->getParameter('rest_api_agent_url')."/BankContract");
        $contractOut->setApiKey($this->getParameter('rest_api_agent_key'));
        $contractOut->setPostFlag(1);
        $contractOut->setJsonFlag(1);
        $contractOut->setPostFields(json_encode($result));
        $contractOut->rawApiConnector();
        if ($contractOut->getHttpCode() == 200) {
            $query = $em->prepare("CALL updateStatusSentToCentral (:contractId)");
            $query->execute([":contractId" => $request->get('contractId')]);
            $returnData = array("status" => "OK");
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($returnData, 200);
        } else {
            $returnData = array("status" => "FAIL");
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($returnData, 404);
        }
    }
	
	/**
     * @Route("/upload", name="upload")
     */
    public function uploadFilesToCentral(Request $request)
    {
        if (!$request->isMethod('POST')) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse('OPTION request', 200);
        }else{
            $req = $request->request->all();
            $type = $req["filetype"];
            $caseId = $req["id"];

            $req = $request->files->get('files');
            $dir = $this->getParameter('temp_dir');
            foreach ($request->files as $uploadedFile)
            {
                $file = $uploadedFile->move($dir, $req->getClientOriginalName());
            }

            $cFile = curl_file_create($dir.$req->getClientOriginalName(),$req->getClientMimeType(),$req->getClientOriginalName());
            $postFields = array('file_contents'=> $cFile, 'writeTypeId' => $type);

            $caseObj = new RestApiInterface();
            $caseObj->setUrl($this->getParameter('rest_api_agent_url')."BankContract/".$caseId."/Files");
            $caseObj->setApiKey($this->getParameter('rest_api_agent_key'));
            $caseObj->setPostFlag(1);
            $caseObj->setPostFields($postFields);
            $caseObj->rawApiConnector();

            if ($caseObj->getHttpCode() == 201) {
                $returnData = array("status" => "FAIL");
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($returnData, 201);
            }
            else {
                $returnData = array("status" => "FAIL");
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($returnData, 404);
            }
        }
    }

    /**
     * @Route("/bankcontractget/{contractid}", name="bankcontractget")
     */
    public function getContract(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL bank_contract_get (:contractid)");
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
     * @Route("/dictionaryGetBankList/{bank_type}", name="dictionary_get_bank_list")
     */
    public function dictionaryGetBankList(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL dictionary_get_bank_list (:bank_type)");
        $query->execute([":bank_type" => $request->get('bank_type')]);
        $result = $query->fetchall();

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
     * @Route("/dictionaryGetCreditType", name="dictionaryGetCreditType")
     */
    public function dictionaryGetCreditType(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL dictionary_get_credit_type ()");
        $query->execute();
        $result = $query->fetchall();

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
     * używane w moduly/franki2.0/src/documents.php w datasource grup uprawnień dokumentu
     *
     * @Route("/getusergroups/{documentid}", defaults={"documentid"=0}, name="getusergroups")
     */
    public function getUserGroup(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $msg = '';

        $em = $this->getDoctrine()->getManager('olddb')->getConnection();
        $query = $em->prepare("CALL documents_get_user_group_list ()");
        $query->execute();
        $userGroupList = $query->fetchall();

        if ($request->get('documentid') == 0) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($userGroupList, 201);
        }

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL documents_get_user_groups ( :documents_id)");
        try {
            $query->execute([
                ":documents_id" => intval($request->get('documentid')),
            ]);
        } catch (\Exception $e) {
            $msg = "MESSAGE3:  ".$e->getMessage();
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($msg, 401);
        }
        $currentDocumentGroups = $query->fetchall();

        $simpleCurrentDocumentGroups = [];
        foreach ($currentDocumentGroups as $row) {
            array_push($simpleCurrentDocumentGroups, $row['user_group_id']);
        }

        foreach ($userGroupList as &$userGroup) {
            if (in_array($userGroup['id'], $simpleCurrentDocumentGroups)) {
                $userGroup['checked'] = true;
            }
        }

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse($userGroupList, 201);
    }


    /**
     * używane w moduly/franki2.0/src/documents.php w datasource gridu przypisanych użytkowników dla konkretnego dokumentu
     *
     * @Route("/getassignedusers/{documentid}", defaults={"documentid"=0}, name="getassignedusers")
     */
    public function getAssignedUsers(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $msg = '';

        if ($request->get('documentid') == 0) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse([], 201);
        }

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL documents_get_assigned_users ( :documents_id)");
        try {
            $query->execute([
                ":documents_id" => intval($request->get('documentid')),
            ]);
        } catch (\Exception $e) {
            $msg = "MESSAGE3:  ".$e->getMessage();
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($msg, 401);
        }
        $assignedUsersId = $query->fetchall();

        $assignedUsers = [];

        $em = $this->getDoctrine()->getManager('olddb')->getConnection();
        foreach ($assignedUsersId as $assigneduserId) {
            $query = $em->prepare("CALL uzytkownik_pobierz_po_id_z_grupami ( :user_id)");
            $query->execute([
                ":user_id" => $assigneduserId['user_id'],
            ]);
            $assignedUser = $query->fetchall();

            array_push($assignedUsers, $assignedUser[0]);
        }

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse($assignedUsers, 201);
    }


    /**
     * używane w moduly/franki2.0/src/documents.php w ajaxie i w datasource gridu pojedyńczych użytkowników do przypisania uprawnień
     *
     * @Route("/addusertodocument", name="addusertodocument")
     */
    public function addUserToDocument(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $data = $request->request->all();

        if (!$data) {
            return new Response('{ "errors": ["Brak danych", "Wprowadź dane"] }', 401);
        }

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL document_add_user ( :document_id, :user_id)");
        try {
            $query->execute([
                ":document_id" => intval($request->get('document_id')),
                ":user_id" => intval($request->get('user_id')),
            ]);
        } catch (\Exception $e) {
            $msg = "MESSAGE3:  ".$e->getMessage();
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($msg, 401);
        }

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse("Dodano uzytkownika", 201);
    }


    /**
     * używane w moduly/franki2.0/src/documents.php w ajaxie i w datasource gridu pojedyńczych użytkowników do przypisania uprawnień
     *
     * @Route("/removeuserdocument", name="removeuserdocument")
     */
    public function removeUserFromDocument(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $data = $request->request->all();

        if (!$data) {
            return new Response('{ "errors": ["Brak danych", "Wprowadź dane"] }', 401);
        }

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL document_remove_user ( :document_id, :user_id )");
        try {
            $query->execute([
                ":document_id" => intval($request->get('documentId')),
                ":user_id" => intval($request->get('user_id')),
            ]);
        } catch (\Exception $e) {
            $msg = "MESSAGE3:  ".$e->getMessage();
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($msg, 401);
        }

        $query->closeCursor();

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse('Pomyslnie usunieto uzytkownika przypisanego do dokumentu.', 201);
    }


    /**
     * używane w moduly/franki2.0/src/documents.php w datasource gridu pojedyńczych użytkowników do przypisania uprawnień
     *
     * @Route("/getusers", name="getusers")
     */
    public function getUsers(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $msg = '';

        $em = $this->getDoctrine()->getManager('olddb')->getConnection();
        $query = $em->prepare("CALL uzytkownik_pobierz_wszystkich_z_grupami ()");

        try {
            $query->execute();
        } catch (\Exception $e) {
            $msg = "MESSAGE3:  ".$e->getMessage();
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($msg, 401);
        }
        $userList = $query->fetchall();

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse($userList, 201);
    }


    /**
     * używane w moduly/franki2.0/src/getFile/getFile.php do pobrania pliku
     *
     * @Route("/dowloaddocument/{documentid}", name="dowloaddocument")
     */
    public function downloadAction(Request $request)
    {
        $msg = '';
        $em = $this->getDoctrine()->getManager()->getConnection();

        $query = $em->prepare("CALL document_get ( :doc_id)");

        try {
            $query->execute([
                ":doc_id" => intval($request->get('documentid')),
            ]);
        } catch (\Exception $e) {
            $msg .= $e->getMessage();

            return new JsonResponse("ERROR MESSAGE1:  ".$msg, 401);
        }

        $file = $query->fetchall();
        $filename = $this->getParameter('docs_directory').$file[0]['doc_file'];
        $fileTitle = $file[0]['doc_file_title'];

        return $this->file($filename, $fileTitle);
    }


    /**
     * używane w moduly/franki2.0/js/functions_documents.js do asynchronicznego uploadu pliku przez kendoUpload
     *
     * @Route("/uploadDocumentFile", name="uploadDocumentFile")
     */
    public function uploadDocumentFileAction(Request $request)
    {
        if (!$request->isMethod('POST')) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse('OPTION request', 200);
        }

        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        if ($request->get('uploaded_doc_id')) {
            $documentId = $request->get('uploaded_doc_id');
            $editFile = true;
        }

        if (empty($_FILES['files']) and ($_FILES['files']['size'] < 1)) {
            return new Response('{ "errors": ["Brak pliku", "Nie przesłano pliku"] }', 401);
        }

        $file = new UploadedFile(
            $_FILES['files']['tmp_name'],
            $_FILES['files']['name'],
            $_FILES['files']['type'],
            $_FILES['files']['size'],
            $_FILES['files']['error']
        );
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $msg = '';

        try {
            $file->move($this->getParameter('docs_directory'), $fileName);
        } catch (FileException $e) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse('Błąd zapisu pliku, error: '.$e->getMessage());
        }

        $em = $this->getDoctrine()->getManager()->getConnection();
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        if ($editFile) {
            $query = $em->prepare("CALL document_file_update ( :doc_id, :doc_file, :doc_file_title, :doc_type, :date_mod)");
            try {
                $query->execute([
                    ":doc_id" => $documentId,
                    ":doc_file" => $fileName,
                    ":doc_file_title" => $file->getClientOriginalName(),
                    ":doc_type" => $file->getClientMimeType(),
                    ":date_mod" => $now,
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();

                return new JsonResponse("ERROR MESSAGE1:  ".$msg, 401);
            }
        }

        if (!$editFile) {
            $query = $em->prepare("CALL document_add ( :id, :doc_file, :doc_file_title, :date_add, :date_mod, :doc_type, 
            :doc_desc, :doc_name)");
            try {
                $query->execute([
                    ":id" => null,
                    ":doc_file" => $fileName,
                    ":doc_file_title" => $_FILES['files']['name'],
                    ":doc_type" => $_FILES['files']['type'],
                    ":date_add" => $now,
                    ":date_mod" => $now,
                    ":doc_desc" => $request->get('description'),
                    ":doc_name" => $request->get('name'),
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();

                return new JsonResponse("ERROR MESSAGE1:  ".$msg, 401);
            }
            $query->closeCursor();
            $id = $em->query("SELECT LAST_INSERT_ID() FROM documents; ")->fetch();
            $documentId = $id['LAST_INSERT_ID()'];
        }

        $dataResponse[] =
            array(
                "uploaded_doc_id" => $documentId,
                "doc_file" => $fileName,
                "doc_file_title" => $_FILES['files']['name'],
                "date_add" => $now,
                "date_mod" => $now,
                "doc_type" => $_FILES['files']['type'],
                "doc_desc" => $request->get('description'),
                "doc_name" => $request->get('name'),
            );

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse($dataResponse, 200);
    }


    /**
     * używane w datasource gridu w moduly/franki2.0/js/functions_documents.js
     *
     * @Route("/createdocument", name="createdocument")
     */
    public function createdocumentAction(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $models = json_decode($request->get('models'));
        $uploadedDocument = $models[0];

        $dataResponse[] =
            [
                "doc_id" => $uploadedDocument->uploaded_doc_id,
                "uploaded_doc_id" => $uploadedDocument->uploaded_doc_id,
                "doc_desc" => $uploadedDocument->doc_desc,
                "doc_name" => $uploadedDocument->doc_name,
                "date_add" => $uploadedDocument->date_add,
                "date_mod" => $uploadedDocument->date_mod,
                "doc_file_title" => $uploadedDocument->doc_file_title,
                "doc_file" => $uploadedDocument->doc_file,
                "doc_type" => $uploadedDocument->doc_type,
            ];

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse($dataResponse, 200);
    }


    /**
     * używane w datasource gridu w moduly/franki2.0/js/functions_documents.js
     *
     * @Route("/getdocumentslist", name="getdocumentslist")
     */
    public function getDocumentsList(Request $request)
    {
        $user = $request->get('user');

        $em = $this->getDoctrine()->getManager('olddb')->getConnection();
        $query = $em->prepare("CALL uzytkownik_pobierz_grupe_po_login (:uzytkownik_id_login)");
        $query->execute([":uzytkownik_id_login" => $user]);
        $userGroupAll = $query->fetchAll();
        $userGroupId = $userGroupAll[0]['uzytkownik_grupa_id'];

        $em = $this->getDoctrine()->getManager()->getConnection();
        $query = $em->prepare("CALL documents_get_list_group (:user_group_id)");
        $query->execute([":user_group_id" => $userGroupId]);
        $result = $query->fetchAll();

        foreach ($result as $key => $row) {
            $createDate = new \DateTime($row["date_add"]);
            $stripDateAdd = $createDate->format('Y-m-d H:i:s');
            $createDateMod = new \DateTime($row["date_mod"]);
            $stripDateMod = $createDateMod->format('Y-m-d H:i:s');

            $arr[] =
                array(
                    "doc_id" => $row["id"],
                    "uploaded_doc_id" => $row["id"],
                    "doc_file" => $row["doc_file"],
                    "doc_file_title" => $row["doc_file_title"],
                    "date_add" => $stripDateAdd,
                    "date_mod" => $stripDateMod,
                    "doc_type" => $row["doc_type"],
                    "doc_desc" => $row["doc_desc"],
                    "doc_name" => $row["doc_name"],
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
     * używane w datasource gridu w moduly/franki2.0/js/functions_documents.js
     *
     * @Route("/removedocument", name="removedocument")
     */
    public function removedocumentAction(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $models = json_decode($request->get('models'));
        $uploadedDocument = $models[0];

        $em = $this->getDoctrine()->getManager()->getConnection();

        $query = $em->prepare("CALL document_file_remove ( :doc_id)");

        try {
            $query->execute([
                ":doc_id" => $uploadedDocument->doc_id,
            ]);
        } catch (\Exception $e) {
            $msg = $e->getMessage();

            return new JsonResponse("ERROR MESSAGE:  ".$msg, 401);
        }

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse('Pomyslnie usunieto dokument', 204);
    }

    /**
     * używane w datasource gridu w moduly/franki2.0/js/functions_documents.js
     *
     * @Route("/updatedocuments", name="updatedocuments")
     */
    public function updatedocumentsAction(Request $request)
    {
        $models = json_decode($request->get('models'));
        $uploadedDocument = $models[0];

        $em = $this->getDoctrine()->getManager()->getConnection();

        $query = $em->prepare("CALL document_update ( :doc_id, :date_mod, :doc_desc, :doc_name)");
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        try {
            $query->execute([
                ":doc_id" => $uploadedDocument->uploaded_doc_id,
                ":date_mod" => $now,
                ":doc_desc" => $uploadedDocument->doc_desc,
                ":doc_name" => $uploadedDocument->doc_name,
            ]);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return new JsonResponse("ERROR MESSAGE1:  ".$msg, 401);
        }

        $dataResponse[] =
            [
                "doc_id" => $uploadedDocument->uploaded_doc_id,
                "uploaded_doc_id" => $uploadedDocument->uploaded_doc_id,
                "doc_desc" => $uploadedDocument->doc_desc,
                "doc_name" => $uploadedDocument->doc_name,
                "date_add" => $uploadedDocument->date_add,
                "date_mod" => $now,
                "doc_file_title" => $uploadedDocument->doc_file_title,
                "doc_file" => $uploadedDocument->doc_file,
                "doc_type" => $uploadedDocument->doc_type,
            ];

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse($dataResponse, 200);
    }

    /**
     * używane w Ajax w edycji popup gridu przy przypisywaniu uprawnień grupom w moduly/franki2.0/src/documents.php
     *
     * @Route("/adddocumentgroups", name="adddocumentgroups")
     */
    public function addDocumentGroupsAction(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $msg = '';
        $documentId = $request->get('doc_id');
        $groups = $request->get('groups');
        $groups = json_decode($groups);

        $em = $this->getDoctrine()->getManager()->getConnection();

        $query = $em->prepare("CALL document_group_delete ( :doc_id)");

        try {
            $query->execute([
                ":doc_id" => intval($documentId),
            ]);
        } catch (\Exception $e) {
            $msg .= $e->getMessage();

            return new JsonResponse("ERROR MESSAGE1:  ".$msg, 401);
        }
        $query->closeCursor();

        foreach ($groups as $group){
            $query = $em->prepare("CALL document_add_group ( :documents_id, :user_group_id )");
            try {
                $query->execute([
                    ":documents_id" => intval($documentId),
                    ":user_group_id" => intval($group->value),
                ]);
            } catch (\Exception $e) {
                $msg .= $e->getMessage();
                return new JsonResponse("MESSAGE2:  ".$msg, 401);
            }
        }

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse('Pomyslnie dodano grupy', 200);
    }
}
