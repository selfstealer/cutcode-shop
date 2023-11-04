<?php

declare(strict_types=1);

namespace Support\Logging\Telegram;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Services\Telegram\Exceptions\TelegramBotApiException;
use Services\Telegram\TelegramBotApi;
use Services\Telegram\TelegramBotApiContract;

final class TelegramLoggerHandler extends AbstractProcessingHandler
{
    protected int $chatId;
    protected string $token;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);

        parent::__construct($level);

        $this->chatId = (int)$config['chat_id'];
        $this->token = $config['token'];
    }

    /**
     * @throws TelegramBotApiException
     */
    protected function write(LogRecord $record): void
    {
        app(TelegramBotApiContract::class)::sendMessage(
            $this->token,
            $this->chatId,
            // $record['formatted'] работать будет потому что LogRecord implements ArrayAccess
            $record->formatted
        );
    }
}
