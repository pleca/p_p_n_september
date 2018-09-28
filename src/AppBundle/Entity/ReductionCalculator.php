<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ReductionCalculator
{
    /**
     * Kredyt
     *
     * @var
     *
     * @Assert\NotBlank(message="Please enter a credit amount")
     * @Assert\Type(
     *     type="numeric",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\GreaterThan(
     *     value = -1,
     *     message="Please enter positive number for a credit amount"
     * )
     */
    private $credit;

    /**
     * Roszczenie
     *
     * @var
     *
     * @Assert\NotBlank(message="Please enter a claim amount")
     * @Assert\Type(
     *     type="numeric",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\GreaterThan(
     *     value = -1,
     *     message="Please enter positive number for a decreased claim amount"
     * )
     */
    private $claim;

    /**
     * Kapitał mniejszy o:
     *
     * @var
     *
     * @Assert\NotBlank(message="Please enter a decreased capital amount")
     * @Assert\Type(
     *     type="numeric",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\GreaterThan(
     *     value = -1,
     *     message="Please enter positive number for a decreased capital amount"
     * )
     */
    private $decreasedCapital;

    /**
     * @return mixed
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * @param mixed $credit
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
    }

    /**
     * @return mixed
     */
    public function getClaim()
    {
        return $this->claim;
    }

    /**
     * @param mixed $claim
     */
    public function setClaim($claim)
    {
        $this->claim = $claim;
    }

    /**
     * @return mixed
     */
    public function getDecreasedCapital()
    {
        return $this->decreasedCapital;
    }

    /**
     * @param mixed $decreasedCapital
     */
    public function setDecreasedCapital($decreasedCapital)
    {
        $this->decreasedCapital = $decreasedCapital;
    }


}