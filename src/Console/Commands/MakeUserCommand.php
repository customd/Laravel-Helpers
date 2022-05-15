<?php

namespace CustomD\LaravelHelpers\Console\Commands;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Console\Command;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\UserProvider;
use Spatie\Permission\Models\Role;

class MakeUserCommand extends Command
{
    protected $description = 'Creates an user.';

    protected $signature = 'make:cd-user';

    protected function getUserData(): array
    {
        $roles = Role::orderBy('id')->pluck('name')->toArray();

        return [
            'name'     => $this->validateInput(fn () => $this->ask('Name'), 'name', ['required']),
            'email'    => $this->validateInput(fn () => $this->ask('Email address'), 'email', ['required', 'email', 'unique:' . $this->getUserModel()]),
            'password' => $this->validateInput(
                fn () => $this->secret('Password'),
                'password',
                ['required', 'min:8']
            ),
            'roles'    => $this->validateInput(
                fn () => $this->choice('User Roles', $roles, null, null, true),
                'roles',
                ['required']
            ),
        ];
    }

    protected function createUser(): User
    {
        $udata = $this->getUserData();

        $user = static::getUserModel()::create($udata);
        $user->assignRole($udata['roles']);

        return $user;
    }

    protected function sendSuccessMessage(User $user): void
    {

        $this->info('Success! ' . ($user->getAttribute('email') ?? $user->getAttribute('username') ?? 'You') . " may now log in.");
    }

    protected function getAuthGuard(): AuthManager
    {
        return auth();
    }

    protected function getUserProvider(): UserProvider
    {
        return $this->getAuthGuard()->getProvider();
    }

    protected function getUserModel(): string
    {
        /** @var EloquentUserProvider $provider */
        $provider = $this->getUserProvider();

        return $provider->getModel();
    }

    public function handle(): int
    {
        $user = $this->createUser();

        $this->sendSuccessMessage($user);

        return static::SUCCESS;
    }

    protected function askRequired(string $question, string $field): string
    {
        return $this->validateInput(fn () => $this->ask($question), $field, ['required']);
    }

    protected function validateInput(Closure $callback, string $field, array $rules): string|array
    {
        $input = $callback();

        $validator = Validator::make(
            [$field => $input],
            [$field => $rules],
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            $input = $this->validateInput($callback, $field, $rules);
        }

        return $input;
    }
}
