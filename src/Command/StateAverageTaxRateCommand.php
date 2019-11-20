<?php

namespace App\Command;

use App\Service\TaxCalculatorFactory;
use App\Util\Main\EntityHelper as MainEntityHelper;
use App\Util\Mysql\EntityHelper as MysqlEntityHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StateAverageTaxRateCommand extends Command
{

    /**
     * @var TaxCalculatorFactory
     */
    private $taxCalculatorFactory;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param TaxCalculatorFactory $taxCalculatorFactory
     * @param ValidatorInterface $validator
     */
    public function __construct(TaxCalculatorFactory $taxCalculatorFactory, ValidatorInterface $validator)
    {
        $this->taxCalculatorFactory     = $taxCalculatorFactory;
        $this->validator                = $validator;

        parent::__construct();
    }

    protected static $defaultName = 'app:taxes:state:rate:average';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Output the average tax rate per state')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to output the the average tax rate per state')
            ->addOption(
                'source',
                null,
                InputOption::VALUE_REQUIRED,
                'Option for providing source of the data [default|mysql]',
                'default'
            )->addOption(
                'country',
                null,
                InputOption::VALUE_REQUIRED,
                'Option for providing country name',
                ''
            )->addOption(
                'state',
                null,
                InputOption::VALUE_REQUIRED
            );;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $violations = $this->validator->validate($input->getOption('source'), [
            new NotBlank(),
            new Choice([MainEntityHelper::EM, MysqlEntityHelper::EM])
        ]);
        $violations->addAll($this->validator->validate($input->getOption('country'), [
            new NotBlank([
                'message' => 'Country cant be empty',
            ])
        ]));
        $violations->addAll($this->validator->validate($input->getOption('state'), [
            new NotBlank([
                'message' => 'State cant be empty',
            ])
        ]));
        if (0 !== count($violations)) {
            foreach ($violations as $violation) {
                throw new \RuntimeException(
                    $violation->getMessage()
                );
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->taxCalculatorFactory->create(
            $input->getOption('source')
        )->calculateAverageTaxRatePerState(
            $input->getOption('country'),
            $input->getOption('state')
        );
        $output->writeln([
            '<info>The average county tax rate per state</info>',
            '<info>===============================================</info>',
            $result
        ]);
    }

}
