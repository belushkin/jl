<?php

namespace App\Service;

use App\Service\Main\TaxCalculator as mainTaxCalculator;
use App\Service\Mysql\TaxCalculator as mysqlTaxCalculator;

class TaxCalculatorFactory
{

    /**
     * @var mainTaxCalculator
     */
    private $mainTaxCalculator;

    /**
     * @var mysqlTaxCalculator
     */
    private $mysqlTaxCalculator;

    public function __construct(mainTaxCalculator $mainTaxCalculator, mysqlTaxCalculator $mysqlTaxCalculator)
    {
        $this->mainTaxCalculator = $mainTaxCalculator;
        $this->mysqlTaxCalculator = $mysqlTaxCalculator;
    }

    /**
     * @param string $source
     * @return TaxCalculatorInterface
     */
    public function create($source = 'default')
    {
        if ($source == 'default') {
            return $this->mainTaxCalculator;
        }
        return $this->mysqlTaxCalculator;
    }
}
