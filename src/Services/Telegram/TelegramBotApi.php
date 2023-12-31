<?php

declare(strict_types=1);

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\Exceptions\TelegramBotApiException;
use Throwable;

class TelegramBotApi implements TelegramBotApiContract
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function fake(): TelegramBotApiFake
    {
        return app()->instance(
            TelegramBotApiContract::class,
            new TelegramBotApiFake()
        );
    }

    /**
     * @throws TelegramBotApiException
     */
    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            return Http::get(self::HOST . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' =>  $text,
            ])->throw()->json('ok', false);
        } catch (Throwable $e) {
            throw new TelegramBotApiException($e->getMessage(), $e->getCode(), $e);

            // сомнительно, что-то решать начинает, какая-то ответственность дополнительная приплетается
//            report(new TelegramBotException($e->getMessage(), $e->getCode(), $e));
//            return false;
        }
    }
}
