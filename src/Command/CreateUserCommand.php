<?php

namespace PlumTreeSystems\UserBundle\Command;

use PlumTreeSystems\UserBundle\Exception\UserException;
use PlumTreeSystems\UserBundle\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * CreateUserCommand constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        parent::__construct();
        $this->userManager = $userManager;
    }

    protected function configure()
    {
        $this
            ->setName('pts:user:create')
            ->setDescription('Creates new user.')
            ->setHelp("Creates new user. Provide email and password.");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);
        $questionEmail = new Question('Enter your Email: ');
        $questionPassword = new Question('Enter your Password: ');
        $questionRole = new Question('Enter defined role (role example: ROLE_ADMIN)');
        $manager = $this->userManager;

        $helper = $this->getHelper('question');

        $email = $helper->ask($input, $output, $questionEmail);
        $password = $helper->ask($input, $output, $questionPassword);
        $role = $helper->ask($input, $output, $questionRole);
        if ($role === '') {
            $role = null;
        }

        try {
            $manager->createUser($email, $password, $role);
            $output->writeln("User $email has been crated");
        } catch (UserException $e) {
            $output->writeln($e->getMessage());
        }
    }
}
