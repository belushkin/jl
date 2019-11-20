<?php

namespace App\Service\Main;

use App\Entity\Main\Country;
use App\Entity\Main\Tax;
use App\Service\EntityListerInterface;

use App\Util\Main\EntityHelper;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;

class EntityLister implements EntityListerInterface
{

    const EM = 'default';

    /**
     * @var ManagerRegistry
     */
    private $manager;

    /**
     * @var EntityHelper
     */
    private $entityHelper;

    /**
     * @param ManagerRegistry $manager
     * @param EntityHelper $entityHelper
     */
    public function __construct(ManagerRegistry $manager, EntityHelper $entityHelper)
    {
        $this->manager = $manager;
        $this->entityHelper = $entityHelper;
    }

    /**
     * @return array
     */
    public function countryList(): array
    {
        return $this->manager->getRepository(Country::class, self::EM)->
        findAll();
    }

    /**
     * @param string $countryName
     * @return Collection|array
     */
    public function stateList(string $countryName)
    {
        $country = $this->entityHelper->getCountry($countryName);
        if ($country == null) {
            return [];
        }
        return $country->getStates();
    }

    /**
     * @param string $countryName
     * @param string $stateName
     * @return Collection|array
     */
    public function countyList(string $countryName, string $stateName)
    {
        $country    = $this->entityHelper->getCountry($countryName);
        if ($country == null) {
            return [];
        }
        $state      = $this->entityHelper->getStateByCountry($country, $stateName);
        if ($state == null) {
            return [];
        }
        return $state->getCounties();
    }

    /**
     * @param string $countryName
     * @param string $stateName
     * @param string $countyName
     * @return Tax[]|Collection
     */
    public function taxesList(string $countryName, ?string $stateName, ?string $countyName)
    {
        $country    = $this->entityHelper->getCountry($countryName);
        $state      = null;
        $county     = null;

        if ($country == null) {
            return [];
        }

        if ($stateName) {
            $state      = $this->entityHelper->getStateByCountry($country, $stateName);
        }
        if ($state && $countyName) {
            $county     = $this->entityHelper->getCountyByState($state, $countyName);
        }

        if ($county) {
            return $county->getTaxes();
        } else if ($state) {
            return $this->entityHelper->getTaxesByState($country, $state);
        }
        return $this->entityHelper->getTaxesByCountry($country);
    }

}
