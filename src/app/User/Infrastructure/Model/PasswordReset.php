<?php

namespace App\User\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = 'token';

    protected $keyType = 'string';
    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
