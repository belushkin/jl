<?php

namespace App\DataFixtures;

use App\Entity\Main\Country as MainCountry;
use App\Entity\Main\County as MainCounty;
use App\Entity\Main\State as MainState;
use App\Entity\Main\Tax as MainTax;

use App\Entity\Mysql\Country as MysqlCountry;
use App\Entity\Mysql\County as MysqlCounty;
use App\Entity\Mysql\State as MysqlState;
use App\Entity\Mysql\Tax as MysqlTax;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $driverName = $manager->getConnection()->getDriver()->getName();
        if ($driverName == 'pdo_sqlite') {
            $this->loadMainFixtures($manager);
        } else {
            $this->loadMysqlFixtures($manager);
        }
    }

    private function loadMainFixtures(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('en_US');
        $faker->addProvider(new \Faker\Provider\en_US\Address($faker));

        $country = new MainCountry();
        $country->setName($faker->country);
        $manager->persist($country);

        for ($i = 0; $i < 5; $i++) {
            $state = new MainState();
            $state->setName($faker->state);
            $state->setCountry($country);
            $country->addState($state);

            $manager->persist($country);
            $manager->persist($state);

            for ($j = 0; $j < 10; $j++) {
                $county = new MainCounty();
                $county->setTaxRate($faker->numberBetween($min = 1, $max = 100));
                $county->setState($state);
                $county->setName($faker->city);

                $manager->persist($county);

                for ($k = 0; $k < 20; $k++) {
                    $tax = new MainTax();
                    $tax->setCountry($country);
                    $tax->setCounty($county);
                    $tax->setState($state);
                    $tax->setAmount($faker->numberBetween($min = 100, $max = 10000));
                    $county->addTax($tax);

                    $manager->persist($tax);
                    $manager->persist($county);
                }
            }
        }
        $manager->flush();
    }

    private function loadMysqlFixtures(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('en_US');
        $faker->addProvider(new \Faker\Provider\en_US\Address($faker));

        $country = new MysqlCountry();
        $country->setName($faker->country);
        $manager->persist($country);

        for ($i = 0; $i < 5; $i++) {
            $state = new MysqlState();
            $state->setName($faker->state);
            $state->setCountry($country);
            $country->addState($state);

            $manager->persist($country);
            $manager->persist($state);

            for ($j = 0; $j < 10; $j++) {
                $county = new MysqlCounty();
                $county->setTaxRate($faker->numberBetween($min = 1, $max = 100));
                $county->setState($state);
                $county->setName($faker->city);

                $manager->persist($county);

                for ($k = 0; $k < 20; $k++) {
                    $tax = new MysqlTax();
                    $tax->setCountry($country);
                    $tax->setCounty($county);
                    $tax->setState($state);
                    $tax->setAmount($faker->numberBetween($min = 100, $max = 10000));
                    $county->addTax($tax);

                    $manager->persist($tax);
                    $manager->persist($county);
                }
            }
        }
        $manager->flush();
    }

}
