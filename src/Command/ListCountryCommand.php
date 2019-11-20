<?php

namespace App\Command;

use App\Service\EntityListerFactory;
use App\Util\Main\EntityHelper as MainEntityHelper;
use App\Util\Mysql\EntityHelper as MysqlEntityHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ListCountryCommand extends Command
{

    /**
     * @var EntityListerFactory
     */
    private $entityListerFactory;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param EntityListerFactory $entityListerFactory
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityListerFactory $entityListerFactory, ValidatorInterface $validator)
    {
        $this->entityListerFactory  = $entityListerFactory;
        $this->validator            = $validator;

        parent::__construct();
    }

    protected static $defaultName = 'app:taxes:country:list';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Output the list of countries')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to output the list of countries')
            ->addOption(
                'source',
                null,
                InputOption::VALUE_REQUIRED,
                'Option for providing source of the data [default|mysql]',
                'default'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $violations = $this->validator->validate($input->getOption('source'), [
            new NotBlank(),
            new Choice([MainEntityHelper::EM, MysqlEntityHelper::EM])
        ]);
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
        $result = $this->entityListerFactory->create(
            $input->getOption('source')
        )->countryList();

        $output->writeln([
            '<info>The list of countries</info>',
            '<info>===============================================</info>',
        ]);
        foreach ($result as $item) {
            $output->writeln([
                $item->getName()
            ]);
        }
    }

}
