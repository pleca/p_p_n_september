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
 * @Route("/loancalc", name="loancalc")
 */

class LoanCalculator extends Controller
{

    /**
     * @Route("/calc/{CurencyCode}/{PlnValue}/{ChfValue}/{ExchangeRate}/{Margin}/{AgreementDate}/{LoanPeriod}/{GracePeriod}/{Spread}/{ChfDate}/{PaymentDayOfMonth}", name="calculateloan")
     */
    public function calc(Request $request)
    {
        $api = new ApiInterface();
        if ($api->checkApiKey($request->get('api_key'))) {
            $in = array(

                "PlnValue" => $request->get('PlnValue'),
                "ChfValue" => $request->get('ChfValue'),
                "ExchangeRate" => $request->get('ExchangeRate'),
                "Margin" => $request->get('Margin'),
                "AgreementDate" => date_format(date_create_from_format('d.m.Y', $request->get('AgreementDate')), 'Y-m-d'),
                "LoanPeriod" => $request->get('LoanPeriod'),
                "GracePeriod" => $request->get('GracePeriod'),
                "Spread" => $request->get('Spread'),
                "ChfDate" => date_format(date_create_from_format('d.m.Y', $request->get('ChfDate')), 'Y-m-d'),
                "PaymentDayOfMonth" => $request->get('PaymentDayOfMonth')
            );


            $restAPI = new RestApiInterface();
            $restAPI->setUrl($this->getParameter('frank_api_url') . 'OverpaidInstallments/CHF?'.http_build_query($in));
            $restAPI->setApiKey($this->getParameter('frank_api_key'));
            $restAPI->rawApiConnector();
            if ($restAPI->getHttpCode() == 200) {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse(json_decode($restAPI->getHttpResponse()));
            } else {
                return new JsonResponse(json_decode($restAPI->getHttpResponse()));
                #return new JsonResponse(array("error"=>$restAPI->getHttpCode()));
            }
        } else {
            return new JsonResponse($api->apiKeyError(), 401);
        }
    }


    /**
     * @Route("/exchange/{PlnValue}/{ChfDate}", name="exchangecalculateloan")
     */
    public function frankExchange(Request $request)
    {
        $api = new ApiInterface();
        if ($api->checkApiKey($request->get('api_key'))) {

            $restAPI = new RestApiInterface();
            $restAPI->setUrl($this->getParameter('frank_api_url') . 'Currency/CHF/'.date_format(date_create_from_format('d.m.Y', $request->get('ChfDate')), 'Y-m-d'));
            $restAPI->setApiKey($this->getParameter('frank_api_key'));
            $restAPI->rawApiConnector();

            if ($restAPI->getHttpCode() == 200) {


                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();

                $chf = $request->get('PlnValue') / $restAPI->getHttpResponse();
                $chf = number_format((float)$chf, 2, '.', '');

                $status = array('CHF' => $chf, "chfexchange" => number_format((float)$restAPI->getHttpResponse(), 2, '.', ''));

                return new JsonResponse($status, 200) ;
            } else {
                return new JsonResponse(json_decode($restAPI->getHttpResponse()));
            }
        } else {
            return new JsonResponse($api->apiKeyError(), 401);
        }
    }
}