<?php

namespace App\Service;

use App\Service\Main\EntityLister as mainEntityLister;
use App\Service\Mysql\EntityLister as mysqlEntityLister;

class EntityListerFactory
{

    /**
     * @var mainEntityLister
     */
    private $mainEntityLister;

    /**
     * @var mysqlEntityLister
     */
    private $mysqlEntityLister;

    public function __construct(mainEntityLister $mainEntityLister, mysqlEntityLister $mysqlEntityLister)
    {
        $this->mainEntityLister = $mainEntityLister;
        $this->mysqlEntityLister = $mysqlEntityLister;
    }

    /**
     * @param string $source
     * @return EntityListerInterface
     */
    public function create($source = 'default')
    {
        if ($source == 'default') {
            return $this->mainEntityLister;
        }
        return $this->mysqlEntityLister;
    }
}
