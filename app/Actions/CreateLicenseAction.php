<?php

namespace App\Actions;

use App\Models\License;
use App\Models\Product;
use App\Models\Purchasable;
use App\Models\User;

class CreateLicenseAction
{
    public function execute(User $user, Purchasable $purchasable): License
    {
        return License::create([
            'key' => Str::random(64),
            'user_id' => $user->id,
            'purchasable_id' => $purchasable->id,
            'expires_at' => now()->addYear(),
        ]);
    }
}
