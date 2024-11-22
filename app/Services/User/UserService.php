<?php

namespace App\Services\User;

use App\Models\User;

class UserService
{
    public function getUser(string $phone): ?User
    {
        /**
         * @var User
         */
        return User::query()
            ->where('phone', $phone)
            ->first();
    }

    public function createUser(string $phone): User
    {
        /**
         * @var User
         */
       return User::query()
            ->create([
                'phone' => $phone,
            ]);
    }
}
