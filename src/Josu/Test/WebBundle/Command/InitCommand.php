<?php
namespace Josu\Test\WebBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
        $initCustomer->setName("Ontro");
        $initCustomer->setEmail("triptest@ontro.co.uk");
        $initCustomer->setPassword("triptest");
        $initCustomer->setAddress("Chitty Street");
        $initCustomer->setCity("London");
        $initCustomer->setCountry("United Kingdom");

        $em->persist($initCustomer);
        $em->flush();

        $output->writeln( "Customer 'triptest' created." );
    }
}
