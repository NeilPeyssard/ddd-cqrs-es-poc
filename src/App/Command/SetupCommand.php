<?php

namespace App\Command;

use Shared\Event\ApplicationSetupEvent;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsCommand(name: 'app:setup')]
class SetupCommand extends Command
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Starting application setup');
        $this->eventDispatcher->dispatch(new ApplicationSetupEvent());

        $io->success('Application setup successfully');

        return Command::SUCCESS;
    }
}
