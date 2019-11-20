<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands
//$application->add(new App\Command\StateOverallCommand());
//$application->add(new App\Command\StateAverageCommand());
//$application->add(new App\Command\StateAverageTaxRateCommand());
//$application->add(new App\Command\CountryAverageTaxRateCommand());

$application->run();
