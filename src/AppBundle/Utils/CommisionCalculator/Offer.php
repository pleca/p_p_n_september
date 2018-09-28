<?php
/**
 * Created by PhpStorm.
 * User: pchryplewicz
 * Date: 01.06.2018
 * Time: 13:12
 */

namespace AppBundle\Utils\CommisionCalculator;


abstract class Offer
{
    private $credit; //Kredyt
    private $claim; //Roszczenie
    private $decreasedCapital; //Kapitał mniejszy o:
    protected $netOffer; // Oferta netto
    protected $remunerationOtherClaimsPercentage; // Honorarium brutto od pozostałych roszczeń

    public function __construct($credit, $claim, $decreasedCapital)
    {
        $this->credit = $credit;
        $this->claim = $claim;
        $this->decreasedCapital = $decreasedCapital;
    }

    /**
     * Wpłata wstępna brutto
     *
     * @return float
     */
    private function calculatePrepayment()
    {
        return $this->netOffer * 1.23;
    }

    /**
     * Kwota honorarium brutto
     *
     * @return float|int
     */
    private function calculateRemunerationAmount()
    {
        return $this->remunerationOtherClaimsPercentage * $this->claim;
    }

    /**
     * Kwota honorarium brutto od zmniejszenia salda zadłużenia
     *
     * @return float|int
     */
    private function calculateRemunerationFromReduction()
    {
        return $this->decreasedCapital * $this->remunerationOtherClaimsPercentage;
    }

    /**
     * Suma wynagrodzenia brutto
     *
     * @return float|int
     */
    private function calculateSumRemuneration()
    {
        return
            $this->calculatePrepayment() +
            $this->calculateRemunerationAmount() +
            $this->calculateRemunerationFromReduction();
    }

    /**
     * % od całości korzyści
     *
     * @return float|int
     */
    private function calculateTotalBenefitsPercentage()
    {
        return (round($this->calculateSumRemuneration() /
            ($this->claim + $this->decreasedCapital), 2));
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'netOffer' => $this->netOffer,
            'credit' => $this->credit,
            'claim' => $this->claim,
            'decreasedCapital' => $this->decreasedCapital,
            'remunerationOtherClaimsPercentage' => $this->remunerationOtherClaimsPercentage,
            'prepayment' => $this->calculatePrepayment(),
            'remunerationAmount' => $this->calculateRemunerationAmount(),
            'remunerationFromReduction' => $this->calculateRemunerationFromReduction(),
            'sumRemuneration' => $this->calculateSumRemuneration(),
            'totalBenefitsPercentage' => $this->calculateTotalBenefitsPercentage(),
        ];
    }

}