<?php
namespace Josu\Test\WebBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\DBALException;

use Josu\Test\WebBundle\Entity\Customer;


class InitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('triptest:init')
            ->setDescription('Create the needed initial data in database.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Create first customer to log in the App
        $em = $this->getContainer()->get("doctrine")->getManager();

        $initCustomer = new Customer();

        // Get bcrypt password
        $encoderFactory = $this->getContainer()->get('security.encoder_factory');
        $encoder = $encoderFactory->getEncoder($initCustomer);
        $password = $encoder->encodePassword('triptest', null);

        $initCustomer->setName("Ontro");
        $initCustomer->setEmail("triptest@ontro.co.uk");
        $initCustomer->setPassword($password);
        $initCustomer->setAddress("Chitty Street");
        $initCustomer->setCity("London");
        $initCustomer->setCountry("United Kingdom");

        $em->persist($initCustomer);

        try {
            $em->flush();
            $output->writeln( "Customer 'triptest@ontro.co.uk' created." );
        } catch (DBALException $e) {
            $output->writeln( "Customer 'triptest@ontro.co.uk' already exits." );
        }

    }
}
