# CutCode Shop

## Install
- composer update
- php artisan key:generate
- php artisan storage:link
- php artisan migrate

## Lesson 1
- composer update
- php artisan key:generate
- php artisan storage:link
- .env FILESYSTEM_DISK=public
- \App\Providers\AppServiceProvider::boot() add Helpers
  - Model::preventLazyLoading
  - Model::preventSilentlyDiscardingAttributes
  - DB::whenQueryingForLongerThan
- composer require barryvdh/laravel-debugbar --dev
- composer require laravel/telescope && php artisan telescope:install
- php artisan migrate

просто запустил vite на host и он прописал себя в index.php, который отдаётся контейнером, и это работает)) вроде бы
