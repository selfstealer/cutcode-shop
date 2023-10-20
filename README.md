# CutCode Shop

И докер помучаем.

## Install
- cp .env.example .env
- php artisan key:generate
- php artisan storage:link
- php artisan migrate

## Synopsis
#### Init
- composer update
- php artisan key:generate
- php artisan storage:link
- .env FILESYSTEM_DISK=public
- \App\Providers\AppServiceProvider::boot() add Helpers
    - Model::preventLazyLoading
    - Model::preventSilentlyDiscardingAttributes
    - DB::whenQueryingForLongerThan
- composer require barryvdh/laravel-debugbar --dev
- composer require laravel/telescope --dev && php artisan telescope:install
- php artisan migrate

Запустил vite на host и он прописал себя в public/index.php, который отдаётся контейнером, и это работает)) вроде бы, потом проверю

#### Логер
- добавляем кастомный логер в logging.php
- добавляем сервис `App\Services\Telegram\TelegramBotApi`
  - в телеге ищем BotFather и создаем своего, получаем `token`
- создаём группу в телеге
- добавляем бота в группу в телеге
- проверяем бота getStatus, от туда берём chat_id
- добавляем `App\Logging\Telegram\TelegramLoggerFactory` прокидываем наш обработчик
- добавляем `App\Logging\Telegram\TelegramLoggerHandler` реализуем `write(...)` с вызовом `TelegramBotApi::sendMessage(...)`
- добавляем в `\App\Providers\AppServiceProvider::boot` логирование проблем `logger()->channel('telegram')->debug(...)`

Ох уж этот ssh с github
https://superuser.com/questions/232373/how-to-tell-git-which-private-key-to-use Надо добавить `~/.ssh/config` с указанием конкретного ключа для домена

#### Команда app:install
- php artisan make:command InstallCommand
- меняем сигнатуру, добавляем пояснение, в хендлер накидываем команды
- запускаем docker exec -it cutcode_php-fpm php /var/www/shop/artisan app:install

#### Sentry
Просто ставим Sentry, 14 дней халявы или больше будет, может это полный доступ?

#### Migrate, Factory и Seeds
Просто три модели с фаршем

#### Stubs
Докинули стабов через `stub:publish`, убрали комменты, добавили строгости

#### HasSlug
Создали трейт, магия ларавеля вжух, отрефакторили всё

#### Команда app:refresh
Добавили новую команду, но без реализации дз пока она не очень нужна

#### Faker::imageFile(dir, dir, path)
Собственно создаём свою реализацию на основе Faker::file, добавляем её через FakerServiceProvider, что бы грузить картиночки рандомно из tests\Fixtures\images
