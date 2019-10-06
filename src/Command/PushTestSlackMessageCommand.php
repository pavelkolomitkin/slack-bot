<?php

namespace App\Command;

use App\Model\Application;
use App\Model\Log;
use App\Model\SlackMessage;
use App\Model\User;
use App\Service\SlackBotService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushTestSlackMessageCommand extends Command
{
    /**
     * @var SlackBotService
     */
    private $service;

    protected static $defaultName = 'app:slack:push-message';

    /**
     * @param SlackBotService $service
     *
     * @required
     */
    public function setBotService(SlackBotService $service)
    {
        $this->service = $service;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Sending a message to slack...');

        $user = new User();
        $user->id = 'UNN54DGLT';
        $user->name = 'Pavel';

        $application = new Application();
        $application->id = '1234567';
        $application->name = 'Some important service';

        $logs = [
            new Log('Hi'),
            new Log('This is the bot from service'),
            new Log('Some errors are occur there'),
        ];

        foreach ($logs as $log)
        {
            $this->service->sendNotify($user, $application, $log);
        }
    }
}