<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\ApiInterface;
use AppBundle\Utils\RestApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/case")
 */
class CaseController extends Controller
{
    /**
     * @Route("/getFileTypeDictionaryList")
     */
    public function getFileTypeDictionaryList(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $restAPI = new RestApiInterface();
        $restAPI->setUrl($this->getParameter('rest_api_agent_url')."FileTypeDictionary");
        $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
        $restAPI->rawApiConnector();
        $result = $restAPI->getHttpResponse();

        if ($restAPI->getHttpCode() == 200) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new Response($result);
        } else {
            return new JsonResponse(array("error" => $restAPI->getHttpCode()));
        }
    }


    /**
     * @Route("/addDocumentFile", name="addDocumentFile")
     */
    public function addDocumentFile(Request $request)
    {
        if (!$request->isMethod('POST')) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse('OPTION request', 200);
        }

        $file = new UploadedFile(
            $_FILES['files']['tmp_name'],
            $_FILES['files']['name'],
            $_FILES['files']['type'],
            $_FILES['files']['size'],
            $_FILES['files']['error']
        );

        $agentId = $request->get('agent_number');
        $typeId = $request->get('type_id');
        $caseId = $request->get('case_id');
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $dir = $this->getParameter('temp_dir');
        foreach ($request->files as $uploadedFile)
        {
            $uploadedFile->move($dir, $file->getClientOriginalName());
        }

        $cFile = curl_file_create($dir.$file->getClientOriginalName(),$file->getClientMimeType(),$file->getClientOriginalName());
        $postFields = array('file_contents'=> $cFile, 'writeTypeId' => $typeId);

        $caseObj = new RestApiInterface();
        $caseObj->setUrl($this->getParameter('rest_api_agent_url')."Agent/".$agentId."/Cases/".$caseId."/Files");
        $caseObj->setApiKey($this->getParameter('rest_api_agent_key'));
        $caseObj->setPostFlag(1);
        $caseObj->setPostFields($postFields);
        $caseObj->rawApiConnector();

        if (!$caseObj->getHttpCode() == 201) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new Response('{ "errors": ["", "Nie przesłano pliku"] }', 401);
        }

        $dataResponse =
            [
                "date" => $now,
            ];

        if ($caseObj->getHttpCode() == 201) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();

            return new JsonResponse($dataResponse, 200);
        }
    }


    /**
     * @Route("/getdocumentslist", name="getdocumentslist")
     */
    public function getDocumentsList(Request $request)
    {
        $api = new ApiInterface();
        if (!$api->checkApiKey($request->get('api_key'))) {
            return new Response('{ "errors": ["Brak dostępu", "Błędny api_key"] }', 401);
        }

        $agentNumber = $request->get('agent_number');
        $caseId = $request->get('case_id');

        if (!$agentNumber) {
            return new Response('{ "errors": ["Brak danych wejściowych", "Błędny numer agenta"] }', 401);
        }

        if (!$caseId) {
            return new Response('{ "errors": ["Brak danych wejściowych", "Błędny numer sprawy"] }', 401);
        }

        $restAPI = new RestApiInterface();
        $restAPI->setUrl($this->getParameter('rest_api_agent_url')."Agent/".$agentNumber."/Cases/".$caseId."/Files");
        $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
        $restAPI->rawApiConnector();
        $files = $restAPI->getHttpResponse();

        $filesArr = json_decode($files);
        foreach ($filesArr as &$file){
            $date = new \DateTime($file->date);
            $file->date = $date->format('Y-m-d H:i:s');
        }

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();

        return new JsonResponse($filesArr);
    }


}