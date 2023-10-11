<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Exceptions\Telegram\TelegramBotException;
use Illuminate\Support\Facades\Http;

final class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    /**
     * @throws TelegramBotException
     */
    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            /** @var bool $ok */
            $ok = Http::get(self::HOST . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' =>  $text,
            ])->json('ok', false);
        } catch (\Throwable $e) {
            throw new TelegramBotException($e->getMessage(), $e->getCode(), $e);
        }
        return $ok;
    }
}
