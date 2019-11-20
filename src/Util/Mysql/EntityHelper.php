<?php

namespace App\Util\Mysql;

use App\Entity\Mysql\Country;
use App\Entity\Mysql\County;
use App\Entity\Mysql\State;
use App\Entity\Mysql\Tax;
use App\Util\EntityHelperInterface;

use Doctrine\Common\Persistence\ManagerRegistry;

class EntityHelper implements EntityHelperInterface
{

    const EM = 'mysql';

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
     * @param string|null $countryName
     * @return Country|null
     */
    public function getCountry(?string $countryName)
    {
        /* @var Country $country */
        $country = $this->manager->getRepository(Country::class, self::EM)->
        findOneBy(['name' => $countryName]);

        return $country;
    }

    /**
     * @param string|null $stateName
     * @return State
     */
    public function getState(?string $stateName): State
    {
        /* @var State $state */
        $state = $this->manager->getRepository(State::class, self::EM)->
        findOneBy(['name' => $stateName]);

        return $state;
    }

    /**
     * @param Country $country
     * @param string $stateName
     * @return State|null
     */
    public function getStateByCountry(Country $country, string $stateName)
    {
        /* @var State $state */
        $state = $this->manager->getRepository(State::class, self::EM)->
        findOneBy(['country' => $country, 'name' => $stateName]);

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
