<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Api\ApiProblem;
use AppBundle\Controller\Api\ApiProblemException;
use AppBundle\Controller\Api\ApiValidationController;
use AppBundle\Entity\ReductionCalculator;
use AppBundle\Utils\CommisionCalculator\FiveThousandOffer;
use AppBundle\Utils\CommisionCalculator\TenThousandOffer;
use AppBundle\Utils\CommisionCalculator\TwoThousandFiveHundredOffer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\ApiInterface;
use AppBundle\Utils\RestApiInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Tests\Fixtures\ConstraintAValidator;


/**
 * @Route("/api/commission")
 */
class CommissionCalculatorController extends ApiValidationController
{
    /**
     * @Route("/calc/{credit}/{claim}/{decreasedCapital}", name="commission_calc")
     */
    public function calculatorAction($credit, $claim, $decreasedCapital)
    {
        $credit = $this->toNumber($credit);
        $claim = $this->toNumber($claim);
        $decreasedCapital = $this->toNumber($decreasedCapital);

        $tt = new TenThousandOffer($credit, $claim, $decreasedCapital);
        $ft = new FiveThousandOffer($credit, $claim, $decreasedCapital);
        $ttfh = new TwoThousandFiveHundredOffer($credit, $claim, $decreasedCapital);

        $reductionCalculator = new ReductionCalculator();
        $reductionCalculator->setClaim($claim);
        $reductionCalculator->setDecreasedCapital($decreasedCapital);
        $reductionCalculator->setCredit($credit);

        $this->validateInput($credit);
        $this->validateInput($claim);
        $this->validateInput($decreasedCapital);

        $validator = $this->get('validator');

        $err = $validator->validate($reductionCalculator);
        $this->checkForErrors($err);

        $result = [];

        $result['inputData']['credit'] = $credit;
        $result['inputData']['claim'] = $claim;
        $result['inputData']['decreasedCapital'] = $decreasedCapital;
        $result['results']['tenThousand'] = $tt->getData();
        $result['results']['fiveThousand'] = $ft->getData();
        $result['results']['twoThousandFiveHundred'] = $ttfh->getData();

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $this->getParameter('ajax_allow_domain'));
        $response->send();
        return new JsonResponse($result, 200);

    }

    private function toNumber($value)
    {
        return is_numeric($value) ? (float)$value : $value;
    }

//        $json = json_encode($result);
//        {
//            "inputData":{
//              "credit":300000,
//              "claim":65000,
//              "decreasedCapital":140000
//            },
//            "results":{
//                    "tenThousand":{
//                         "netOffer":10000,
//                         "credit":300000,
//                         "claim":65000,
//                         "decreasedCapital":140000,
//                         "remunerationOtherClaimsPercentage":0.1,
//                         "prepayment":12300,
//                         "remunerationAmount":6500,
//                         "remunerationFromReduction":14000,
//                         "sumRemuneration":32800,
//                         "totalBenefitsPercentage":0.16
//                    },
//                    "fiveThousand":{
//                         "netOffer":5000,
//                         "credit":300000,
//                         "claim":65000,
//                         "decreasedCapital":140000,
//                         "remunerationOtherClaimsPercentage":0.175,
//                         "prepayment":6150,
//                         "remunerationAmount":11375,
//                         "remunerationFromReduction":24500,
//                         "sumRemuneration":42025,
//                         "totalBenefitsPercentage":0.205
//                    },
//                    "twoThousandFiveHundred":{
//                         "netOffer":2500,
//                         "credit":300000,
//                         "claim":65000,
//                         "decreasedCapital":140000,
//                         "remunerationOtherClaimsPercentage":0.3,
//                         "prepayment":3075,
//                         "remunerationAmount":19500,
//                         "remunerationFromReduction":42000,
//                         "sumRemuneration":64575,
//                         "totalBenefitsPercentage":0.315
//                    }
//             }
//          }

}
