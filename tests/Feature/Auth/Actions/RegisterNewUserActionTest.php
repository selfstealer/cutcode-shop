<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Actions;

use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterNewUserActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_success_user_created(): void
    {
        $action = app(RegisterNewUserContract::class);

        $email = 'testing@cutcode.ru';

        $this->assertDatabaseMissing('users', [
            'email' => $email
        ]);

        $action(NewUserDTO::make(
            'test',
            $email,
            '1234567890',
        ));

        $this->assertDatabaseHas('users', [
            'email' => $email
        ]);
    }
}
