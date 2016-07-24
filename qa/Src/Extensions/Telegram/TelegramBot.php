<?php

namespace App\Extensions\Telegram;

use Telegram\Bot\Api;
use Telegram\Bot\HttpClients\GuzzleHttpClientNoVerify;

class TelegramBot {

    private $api;
    private $offset;
    private $updates;
    private $config;

    public function __construct($config)
    {
        $this->api = new Api($config['key'], false, new GuzzleHttpClientNoVerify());
        $this->config = $config;
        $this->offset = $this->getLastUpdate();
    }

    public function getUpdates()
    {
        $this->updates = $this->api->getUpdates(['offset' => $this->offset + 1]);
        return count($this->updates);
    }

    public function processUpdates()
    {

        $questions = [];

        foreach ($this->updates as $update) {
            $msg = $update->getMessage();
            $text = $msg->getText();
            $chatId = $msg->getChat()->getId();
            $entities = $msg->getEntities();

            // skip updates without command or several commands
            if (count($entities) != 1) {
                continue;
            }

            $command = substr($text, $entities[0]['offset'], $entities[0]['length']);

            switch ($command) {
                case '/ask':
                    $msgId = $msg->getMessageId();
                    $date = $msg->getDate();
                    $userName = $msg->getFrom()->getFirstName() . ' ' . $msg->getFrom()->getLastName();
                    $questions[] = [
                        'chat_id' => $chatId,
                        'msg_id' => $msgId,
                        'postdate' => $date,
                        'author_name' => $userName,
                        'author_email' => $this->config['email'],
                        'q' => trim(str_replace('/ask', '', $text)),
                    ];
                    break;
                case '/start':
                case '/help' : {
                    $params = [
                        'chat_id' => $chatId,
                        'text' => "*Я понимаю следующие команды:*\n/ask <вопрос> - задать вопрос"
                    ];
                    $this->sendAnswer($params);
                    break;
                }
            }
        }

        $this->setLastUpdate($update->getUpdateId());

        return $questions;
    }


    public function sendAnswer($params)
    {
        $data = [
            'chat_id' => $params['chat_id'],
            'text' => "*Bot echo:*\n" . $params['text'],
            'parse_mode' => 'Markdown'
        ];

        if (!empty($params['msg_id'])) {
            $data['reply_to_message_id'] = $params['msg_id'];
        }

        $this->api->sendMessage($data);
    }


    private function getLastUpdate() {
        $cache = __DIR__ . "/tel.dat";
        return (is_file($cache))
            ? (int)file_get_contents($cache)
            : 0;
    }

    private function setLastUpdate($id) {
        file_put_contents(__DIR__ . "/tel.dat", (int)$id);
    }

}
