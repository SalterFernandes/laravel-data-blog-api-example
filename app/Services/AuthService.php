<?php

namespace App\Services;

use App\Data\User\UserData;
use App\Data\User\CreateUserData;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

readonly class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function register(CreateUserData $data): array
    {
        $user = $this->userRepository->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => UserData::from($user),
            'token' => $token,
        ];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => UserData::from($user),
            'token' => $token,
        ];
    }

    public function logout(int $userId): void
    {
        $user = $this->userRepository->findById($userId);
        $user?->tokens()->delete();
    }
}
