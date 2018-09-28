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
 * @Route("/contract", name="contract")
 */

class ContractController extends Controller
{
    /**
     * @Route("/get/{id}")
     */
    public function getContractData(Request $request)
    {
        $em = $this->getDoctrine()->getManager('olddb')->getConnection();
        $query = $em->prepare("CALL GetContractData (:id)");
        $query->execute([":id" => $request->get('id')]);
        $result = $query->fetchAll();

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')) )
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($result[0]);
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }

    /**
     * @Route("/centralupdate/{id}")
     */
    public function getContractDataSendToCentralUpdate(Request $request)
    {
        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')) )
        {
            $em = $this->getDoctrine()->getManager('olddb')->getConnection();
            $query = $em->prepare("CALL GetContractData (:id)");
            $query->execute([":id" => $request->get('id')]);
            $result = json_encode($query->fetchAll()[0]);
            $restAPI = new RestApiInterface();
			
            $restAPI->setUrl($this->getParameter('rest_api_agent_url').'Contract');
            $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
            $restAPI->setPostFlag(0);
            $restAPI->setJsonFlag(1);
            $restAPI->setPostFields($result);
            $restAPI->rawApiConnector();
            if ($restAPI->getHttpCode() == 200)
            {
                $em = $this->getDoctrine()->getManager('olddb')->getConnection();
                $query = $em->prepare("CALL GetContractDataSendToCentralUpdate (:id, :val)");
                $query->execute([":id" => $request->get('id'), ":val" => 1]);

                $result = array("ok"=>true);
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($result);
            }
            else
            {
                $result = array("fail"=>$restAPI->getHttpCode());
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($result, 404);
            }

        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }

    /**
     * @Route("/upload/{id}", name="uploadfile")
     */
    public function sendFile(Request $request)
    {
        $req = $request->request->all();
        $type = $req["filetype"];
        $cid = explode("-",$request->get('id'));
        $cid = $cid[0];

       // $result = $cid;
        //return new JsonResponse($result);

        $api = new ApiInterface();

        if ( $api->checkApiKey($request->get('api_key')) )
        {
            if ($request->files->get('file')) {
                foreach ($request->files->get('file') as $uploadedFile) {
                    $filename = sha1($uploadedFile->getClientOriginalName() . microtime()) . "." . $uploadedFile->getClientOriginalExtension();
                    $uploadedFile->move($this->getParameter('temp_dir'), $filename);

                    $cFile = curl_file_create($this->getParameter('temp_dir') . $filename);

                    $postFields = array('writeTypeId' => $request->get('fid'), 'file_contents' => $cFile, 'name' => 'Inne');

                    $restAPI = new RestApiInterface();
                    $restAPI->setUrl($this->getParameter('rest_api_agent_url') . 'Contract/' . $cid . '/files');
                    $restAPI->setApiKey($this->getParameter('rest_api_agent_key'));
                    $restAPI->setPostFlag(1);
                    $restAPI->setPostFields($postFields);
                    $restAPI->rawApiConnector();
                }
                    if ($restAPI->getHttpCode() == 201) {
                        $result = array("ok" => true);
                        $response = new Response();
                        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                        $response->send();
                        return new JsonResponse($result);
                    } else {
                        $result = array("fail" => $restAPI->getHttpCode());
                        $response = new Response();
                        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                        $response->send();
                        return new JsonResponse($result, 404);
                    }

            }
            else {
                $result = array("fail" => "no file");
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse($result, 404);
            }
        }
        else
        {
            return new JsonResponse($api->apiKeyError(),401);
        }
    }

    /**
     * @Route("/getdictionary", name="getdictionary")
     */
    public function getFileDictionary()
    {
        $restAPI = new RestApiInterface();
        $restAPI->setUrl($this->getParameter('rest_api_agent_url').'/FileTypeDictionary');
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
     * @Route("/uploadmail", name="uploadfilemail")
     */
    public function sendFilemail(Request $request)
    {

        if ($request->files->get('file')) {
            $message = \Swift_Message::newInstance()
                ->setSubject('test')
                ->setFrom('automat@votum-sa.pl')
                ->setTo('grzegorz.borycki@votum-sa.pl')
                ->setBody(
                    $this->renderView(
                        'Emailfilessend.html.twig',
                        array('names' => "")
                    ),
                    'text/html'
                );

            foreach ($request->files->get('file') as $uploadedFile) {
                $filename = sha1($uploadedFile->getClientOriginalName().microtime()).".".$uploadedFile->getClientOriginalExtension();
                $uploadedFile->move($this->getParameter('temp_dir'), $filename);
                $message->attach(\Swift_Attachment::fromPath($this->getParameter('temp_dir').$filename)->setFilename($filename));
            }

            $this->get('mailer')->send($message);

            $result = array("ok"=>true);
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
            $response->send();
            return new JsonResponse($result);
        }
        else {
            echo "no file";
        }

    }

}