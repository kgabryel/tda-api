<?php

namespace App\User\Infrastructure\Model;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setFacebookId(string $facebookId): self
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    public function getFbId(): ?string
    {
        return $this->facebook_id;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setNotificationEmail(?string $email): self
    {
        $this->notification_email = $email;

        return $this;
    }

    public function getNotificationEmail(): ?string
    {
        return $this->notification_email;
    }

    public function setActivationCode(?string $code): self
    {
        $this->activation_code = $code;

        return $this;
    }

    public function getActivationCode(): ?string
    {
        return $this->activation_code;
    }

    public function setEmailVerifiedAt(?DateTime $dateTime): self
    {
        $this->email_verified_at = $dateTime;

        return $this;
    }

    public function getEmailVerifiedAt(): ?DateTime
    {
        return $this->email_verified_at;
    }

    public function getSettings(): Settings
    {
        return $this->hasOne(Settings::class, 'user_id', 'id')->first();
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }
}
