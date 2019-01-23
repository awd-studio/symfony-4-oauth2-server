<?php

namespace App\Interfaces\Cli\Command;

use App\Domain\Model\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAddCommand extends Command
{
    protected static $defaultName = 'app:user:add';

    /** @var ObjectManager */
    private $objectManager;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /**
     * ClientAddCommand constructor.
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     */
    public function __construct(ObjectManager $objectManager, UserPasswordEncoderInterface $encoder)
    {
        $this->objectManager = $objectManager;
        $this->encoder = $encoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a user')
            ->addArgument('email', InputArgument::REQUIRED, 'User\'s e-mail')
            ->addArgument('password', InputArgument::REQUIRED, 'The password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf('Not valid E-mail: "%s"', $email));
        }

        $user = User::create($email);
        $password = $this->encoder->encodePassword($user, $password);
        $user->setPassword($password);
        $this->objectManager->persist($user);

        $this->objectManager->flush();

        $id = (string)$user->getId();
        $io->success(sprintf('User "%s" created with an ID: %s', $email, $id));
    }
}
