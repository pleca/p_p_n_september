<?php
/**
 * Created by PhpStorm.
 * User: pchryplewicz
 * Date: 01.06.2018
 * Time: 12:18
 */

namespace AppBundle\Utils\CommisionCalculator;

class FiveThousandOffer extends Offer
{
    const netOffer = 5000;
    const remunerationOtherClaimsPercentage = 0.2; // Honorarium brutto od pozostałych roszczeń

    public function __construct($credit, $claim, $decreasedCapital)
    {
        parent::__construct($credit, $claim, $decreasedCapital);
        $this->netOffer = self::netOffer;
        $this->remunerationOtherClaimsPercentage = self::remunerationOtherClaimsPercentage;
    }
}