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

class ListStateCommand extends Command
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

    protected static $defaultName = 'app:taxes:state:list';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Output the list of states')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to output the list of states')
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
                'Option for providing country name'
            );
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
        $source         = $input->getOption('source');
        $countryName    = $input->getOption('country');

        $result = $this->entityListerFactory->
        create($source)->
        stateList($countryName);

        $output->writeln([
            '<info>The list of states of '.$countryName.'</info>',
            '<info>===============================================</info>',
        ]);
        foreach ($result as $item) {
            $output->writeln([
                $item->getName()
            ]);
        }
    }

}
