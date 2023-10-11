<?php

declare(strict_types=1);

namespace App\Logging\Telegram;

use App\Exceptions\Telegram\TelegramBotException;
use App\Services\Telegram\TelegramBotApi;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

final class TelegramLoggerHandler extends AbstractProcessingHandler
{
    protected int $chatId;
    protected string $token;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);

        parent::__construct($level);

        $this->chatId = $config['chat_id'];
        $this->token = $config['token'];
    }

    /**
     * @throws TelegramBotException
     */
    protected function write(LogRecord $record): void
    {
        TelegramBotApi::sendMessage(
            $this->token,
            $this->chatId,
            // $record['formatted'] работать будет потому что LogRecord implements ArrayAccess
            $record->formatted
        );
    }
}
