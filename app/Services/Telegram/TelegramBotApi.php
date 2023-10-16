<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Exceptions\Telegram\TelegramBotException;
use Illuminate\Support\Facades\Http;
use Throwable;

final class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    /**
     * @throws TelegramBotException
     */
    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            return Http::get(self::HOST . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' =>  $text,
            ])->throw()->json('ok', false);
        } catch (Throwable $e) {
            throw new TelegramBotException($e->getMessage(), $e->getCode(), $e);

            // сомнительно, что-то решать начинает, какая-то ответственность дополнительная приплетается
//            report(new TelegramBotException($e->getMessage(), $e->getCode(), $e));
//            return false;
        }
    }
}
