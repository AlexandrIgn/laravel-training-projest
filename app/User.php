<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name', 'email', 'password', 'status', 'role',
    ];


    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function new($name, $email)
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random()),
            'status' => self::STATUS_ACTIVE,
            'role' => self::ROLE_USER,
        ]);
    }

    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_token' => Str::uuid(),
            'status' => self::STATUS_WAIT,
            'role' => self::ROLE_USER,
        ]);
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already verified.');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }

    public function changeRole($role)
    {
        if (!in_array($role, [self::ROLE_USER, self::ROLE_ADMIN, true])) {
            throw new \InvalidArgumentException('Undedined role "' . $role . '"');
        }
        if ($this->role === $role) {
            throw new \DomainException('Role is already assigned.');
        }
        $this->update(['role' => $role]);
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
