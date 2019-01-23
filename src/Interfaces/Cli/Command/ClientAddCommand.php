<?php

namespace App\Interfaces\Cli\Command;

use App\Domain\Model\Client;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClientAddCommand extends Command
{
    protected static $defaultName = 'app:client:add';

    /** @var ObjectManager */
    private $objectManager;

    /**
     * ClientAddCommand constructor.
     * @param $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a client')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of client')
            ->addArgument('secret', InputArgument::REQUIRED, 'The secret phrase');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $secret = $input->getArgument('secret');

        $client = Client::create($name, $secret);
        $this->objectManager->persist($client);

        $this->objectManager->flush();

        $id = (string)$client->getId();
        $io->success(sprintf('Client "%s" created with an ID: %s', $name, $id));
    }
}
