<?php


namespace App\Service;

use App\Model\Application;
use App\Model\Log;
use App\Model\SlackMessage;
use App\Model\User;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



class SlackBotService
{
    const BASE_URL = 'https://slack.com/api/';

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Client
     */
    private $client;

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger)
    {
        $this->params = $params;
        $token = $this->params->get('slack_bot_token');
        var_dump(['token' => $token]);

        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json;charset=utf-8',
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->logger = $logger;
    }

    public function sendNotify(User $user, Application $application, Log $log)
    {
        $privateChannel = str_replace(' ', '-', strtolower($application->name . '-' . $user->name));

        $response = $this->client->request('POST', $this->getUrl('conversations.create'), [
            'body' => json_encode([
                'name' => $privateChannel,
                'is_private' => true,
                'user_ids' => [$user->id],
            ])
        ]);

        var_dump(['response' => $response->getBody()->getContents()]);

        $response = $this->client->request('POST', $this->getUrl('chat.postMessage'), [
            'body' => json_encode([
                'channel' => $privateChannel,
                'text' => $log->message,
                'as_user' => false,
                'username' => $application->name . ' Bot'
            ])
        ]);

        var_dump(['response' => $response->getBody()->getContents()]);

    }

    public function _sendNotify($userId, $channelName, SlackMessage $message)
    {
        // create a private channel with name $channelName in order to make sure that the channel exists that related to the user $userId

        $serviceChannelName = strtolower($channelName . $userId);

        var_dump(['channelName' => $serviceChannelName]);

        $response = $this->client->request('POST', $this->getUrl('conversations.create'), [
            'body' => json_encode([
                'name' => $serviceChannelName,
                'is_private' => true,
                'user_ids' => [$userId],
            ])
        ]);

        var_export(['response' => $response->getBody()->getContents()]);
//
        $this->logger->log(LogLevel::DEBUG, var_export(['response' => $response->getBody()->getContents()], true));

        // send a message to the private channel

        $response = $this->client->request('POST', $this->getUrl('chat.postMessage'), [
            'body' => json_encode([
                'channel' => $serviceChannelName,
                'text' => 'Hello from service1',
                'as_user' => false,
                'username' => 'My service123'
            ])
        ]);

        var_dump(['response' => $response->getBody()->getContents()]);
    }

    public function sendTestMessage()
    {

    }


    private function getUrl(string $command)
    {
        return self::BASE_URL . $command;
    }
}