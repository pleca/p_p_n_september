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
 * @Route("/address", name="address")
 */

class AddressController extends Controller
{
     /**
     * @Route("/{province}/{district}/{commune}/{city}/{street}", defaults={"province" = null, "district" = null, "commune" = null, "city" = null, "street" = null})
     */
    public function getData(Request $request)
    {
        $url = $this->getParameter('rest_api_url').'address/';

        if ($request->get('province')) {
            $url = $this->getParameter('rest_api_url').'address/'
            .AddressController::str2url($request->get('province')).'?limit=255';
        }

        if($request->get('district')) {
            $url = $this->getParameter('rest_api_url').'address/'
            .AddressController::str2url($request->get('province')).'/'
            .AddressController::str2url($request->get('district')).'?limit=255';
        }

        if($request->get('commune')) {
            $url = $this->getParameter('rest_api_url').'address/'
                .AddressController::str2url($request->get('province')).'/'
                .AddressController::str2url($request->get('district')).'/'
                .AddressController::str2url($request->get('commune')).'?limit=255';
        }

        if($request->get('city')) {
            $url = $this->getParameter('rest_api_url').'address/'
                .AddressController::str2url($request->get('province')).'/'
                .AddressController::str2url($request->get('district')).'/'
                .AddressController::str2url($request->get('commune')).'/'
                .AddressController::str2url($request->get('city')).'?limit=255';
        }

        if($request->get('street')) {
            $url = $this->getParameter('rest_api_url').'address/'
                .AddressController::str2url($request->get('province')).'/'
                .AddressController::str2url($request->get('district')).'/'
                .AddressController::str2url($request->get('commune')).'/'
                .AddressController::str2url($request->get('city')).'/'
                .AddressController::str2url($request->get('street'));
        }

        $api = new ApiInterface();
        if ( $api->checkApiKey($request->get('api_key')))
        {
            $restAPI = new RestApiInterface();
            $restAPI->setUrl($url);
            $restAPI->rawApiConnector();
            if ($restAPI->getHttpCode() == 200)
            {
                $response = new Response();
                $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
                $response->send();
                return new JsonResponse(json_decode($restAPI->getHttpResponse()));
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

    private static function str2url( $str, $replace = "-" )
    {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $charsArr = array( '^', "'", '"', '`', '~');
        $str = str_replace( $charsArr, '', $str );
        $return = trim(preg_replace('# +#',' ',preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($str))));
        return str_replace(' ', $replace, $return);
        return $str;
    }

}