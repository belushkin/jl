<?php

namespace App\Util\Main;

use App\Entity\Main\Country;
use App\Entity\Main\County;
use App\Entity\Main\State;
use App\Entity\Main\Tax;
use App\Util\EntityHelperInterface;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

class EntityHelper implements EntityHelperInterface
{

    const EM = 'default';

    /**
     * @var ManagerRegistry
     */
    private $manager;

    /**
     * @param ManagerRegistry $manager
     */
    public function __construct(ManagerRegistry $manager = null)
    {
        $this->manager = $manager;
    }

    /**
     * @param string|null $country
     * @return Country|null
     */
    public function getCountry(?string $country)
    {
        /* @var Country $country */
        $country = $this->manager->getRepository(Country::class, self::EM)->
        findOneBy(['name' => $country]);

        return $country;
    }

    /**
     * @param string|null $state
     * @return State
     */
    public function getState(?string $state): State
    {
        /* @var State $state */
        $state = $this->manager->getRepository(State::class, self::EM)->
        findOneBy(['name' => $state]);

        return $state;
    }

    /**
     * @param Country $country
     * @param string $state
     * @return State:null
     */
    public function getStateByCountry(Country $country, string $state)
    {
        /* @var State $state */
        $state = $this->manager->getRepository(State::class, self::EM)->
        findOneBy(['country' => $country, 'name' => $state]);

        return $state;
    }

    /**
     * @param State $state
     * @param string $countyName
     * @return County|null
     */
    public function getCountyByState(State $state, string $countyName)
    {
        /* @var County $county */
        $county = $this->manager->getRepository(County::class, self::EM)->
        findOneBy(['state' => $state, 'name' => $countyName]);

        return $county;
    }

    /**
     * @param Country $country
     * @param State $state
     * @return Tax[]
     */
    public function getTaxesByState(Country $country, State $state): array
    {
        /* @var Tax[] $taxes */
        $taxes = $this->manager->getRepository(Tax::class, self::EM)->
        findBy(['country' => $country, 'state' => $state]);

        return $taxes;
    }

    /**
     * @param Country $country
     * @return Tax[]
     */
    public function getTaxesByCountry(Country $country): array
    {
        /* @var Tax[] $taxes */
        $taxes = $this->manager->getRepository(Tax::class, self::EM)->
        findBy(['country' => $country]);

        return $taxes;
    }

}
