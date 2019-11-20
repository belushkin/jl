<?php

namespace App\Service\Main;

use App\Entity\Main\Country;
use App\Entity\Main\County;
use App\Entity\Main\State;
use App\Entity\Main\Tax;
use App\Service\TaxCalculatorInterface;
use App\Util\Main\EntityHelper;
use Doctrine\ORM\EntityManagerInterface;

class TaxCalculator implements TaxCalculatorInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EntityHelper
     */
    private $entityHelper;

    /**
     * @param EntityManagerInterface $entityManager
     * @param EntityHelper $entityHelper
     */
    public function __construct(EntityManagerInterface $entityManager, EntityHelper $entityHelper)
    {
        $this->entityManager = $entityManager;
        $this->entityHelper = $entityHelper;
    }

    /**
     * @param string $countryName
     * @param string $stateName
     * @return int
     */
    public function calculateOverallTaxPerState(string $countryName, string $stateName): int
    {
        /* @var Country $country */
        $country = $this->entityHelper->getCountry($countryName);
        if ($country == null) {
            return 0;
        }

        /* @var State $state */
        $state = $this->entityHelper->getStateByCountry($country, $stateName);
        if ($state == null) {
            return 0;
        }

        /* @var Tax[] $taxes */
        $taxes = $this->entityHelper->getTaxesByState($country, $state);

        $result = 0;
        foreach ($taxes as $tax) {
            $result += $tax->getAmount();
        };
        return $result;
    }

    /**
     * @param string $countryName
     * @param string $stateName
     * @return float
     */
    public function calculateAverageTaxPerState(string $countryName, string $stateName): float
    {
        /* @var Country $country */
        $country = $this->entityHelper->getCountry($countryName);
        if ($country == null) {
            return 0;
        }

        /* @var State $state */
        $state = $this->entityHelper->getStateByCountry($country, $stateName);
        if ($state == null) {
            return 0;
        }

        /* @var Tax[] $taxes */
        $taxes = $this->entityHelper->getTaxesByState($country, $state);

        $result = 0;
        foreach ($taxes as $tax) {
            $result += $tax->getAmount();
        };

        return $result/count($taxes);
    }

    /**
     * @param string $countryName
     * @param string $stateName
     * @return float
     */
    public function calculateAverageTaxRatePerState(string $countryName, string $stateName): float
    {
        /* @var Country $country */
        $country = $this->entityHelper->getCountry($countryName);
        if ($country == null) {
            return 0;
        }

        /* @var State $state */
        $state = $this->entityHelper->getStateByCountry($country, $stateName);
        if ($state == null) {
            return 0;
        }

        /* @var County[] $counties */
        $counties = $state->getCounties();

        $result = 0;
        foreach ($counties as $county) {
            $result += $county->getTaxRate();
        };

        return $result/count($counties);
    }

    /**
     * @param string $countryName
     * @return float
     */
    public function calculateAverageTaxRatePerCountry(string $countryName): float
    {
        /* @var Country $country */
        $country = $this->entityHelper->getCountry($countryName);
        if ($country == null) {
            return 0;
        }
        /* @var State[] $states */
        $states = $country->getStates();

        $result = [];
        foreach ($states as $state) {
            /* @var County[] $counties */
            $counties = $state->getCounties();
            foreach ($counties as $county) {
                $result[] = $county->getTaxRate();
            };
        };

        return array_sum($result)/count($result);
    }

    /**
     * @param string $countryName
     * @return int
     */
    public function calculateOverallTaxPerCountry(string $countryName): int
    {
        /* @var Country $country */
        $country = $this->entityHelper->getCountry($countryName);
        if ($country == null) {
            return 0;
        }
        /* @var Tax[] $taxes */
        $taxes = $this->entityHelper->getTaxesByCountry($country);

        $result = 0;
        foreach ($taxes as $tax) {
            $result += $tax->getAmount();
        };
        return $result;
    }
}
