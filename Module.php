<?php
namespace SwissEngine\Tools\Doctrine\Extension;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Zend\EventManager\Event;
use Zend\ModuleManager\ModuleManagerInterface;

class Module
{
    public function init(ModuleManagerInterface $manager)
    {
        // Make sure this is executed last
        $events = $manager->getEventManager();
        $events->getSharedManager()->attach('doctrine', 'loadCli.post', array(
            $this, 'setEntityManager'
        ), -1000);
    }

    public function setEntityManager(Event $e) {
        /* @var $cli \Symfony\Component\Console\Application */
        $cli = $e->getTarget();

        // Set new option for all commands
        $commands = $cli->all();

        foreach ($commands as $command) {
            $command->getDefinition()->addOption(new InputOption('em', null, InputOption::VALUE_OPTIONAL, 'Set the name of your entity manager (overrides orm_default)'));
        }

        // Get command-line arguments
        $args = new ArgvInput();
        $em   = $args->getParameterOption('--em');

        // Set the new em if needed
        if ($em) {
            /* @var $serviceLocator \Zend\ServiceManager\ServiceLocatorInterface */
            $serviceLocator = $e->getParam('ServiceManager');

            /* @var $entityManager \Doctrine\ORM\EntityManager */
            $entityManager = $serviceLocator->get(sprintf('doctrine.entitymanager.%s', $em));
            $helperSet     = $cli->getHelperSet();
            $helperSet->set(new ConnectionHelper($entityManager->getConnection()), 'db');
            $helperSet->set(new EntityManagerHelper($entityManager), 'em');
        }
    }
}
