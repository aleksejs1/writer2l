<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class W2lUserCreateCommand extends Command
{
    protected static $defaultName = 'w2l:user:create';

    private $userRepository;
    private $passwordEncoder;

    public function __construct(
        string $name = null,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Create new user or update user password')
            ->addArgument('username', InputArgument::OPTIONAL, 'Username')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        if (!$username || !$password) {
            $io->error('Empty arguments.');

            return 1;
        }
        if ($username) {
            $io->note(sprintf('You passed an username: %s', $username));
        }
        if ($password) {
            $io->note(sprintf('You passed a password: %s', $password));
        }
        $message = 'User updated';
        $user = $this->userRepository->findOneBy(['username' => $username]);
        if (!$user) {
            $message = 'User created';
            $user = new User();
            $user
                ->setRoles(['ROLE_ADMIN'])
                ->setUsername($username)
            ;
        }
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $this->userRepository->save($user);

        $io->success($message);

        return 0;
    }
}
