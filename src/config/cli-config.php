<?php
declare(strict_types=1);

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$container = require_once __DIR__ . '/container.php';

$entityManager = $container->get(EntityManager::class);

/**
 * DOCTRINE CLI
 */
$helperSet = ConsoleRunner::createHelperSet($entityManager);
$helperSet->set(new \Symfony\Component\Console\Helper\QuestionHelper(), 'dialog');


/**
 * DOCTRINE MIGRATIONS
 */
$configuration = new Configuration($entityManager->getConnection());
$configuration->setMigrationsDirectory('migrations');
$configuration->setMigrationsNamespace('DoctrineMigrations');

$diff = new DiffCommand();
$exec = new ExecuteCommand();
$gen = new GenerateCommand();
$migrate = new MigrateCommand();
$status = new StatusCommand();
$ver = new VersionCommand();

$diff->setMigrationConfiguration($configuration);
$gen->setMigrationConfiguration($configuration);
$exec->setMigrationConfiguration($configuration);
$migrate->setMigrationConfiguration($configuration);
$status->setMigrationConfiguration($configuration);
$ver->setMigrationConfiguration($configuration);

$cli = ConsoleRunner::createApplication($helperSet,[
    $diff, $exec, $gen, $migrate, $status, $ver
]);

return $cli->run();
