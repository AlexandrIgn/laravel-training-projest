<?php

namespace App\Console\Commands\User;

use Illuminate\Console\Command;
use App\Services\Auth\RegisterService;
use App\User;

class RoleCommand extends Command
{
    private $service;

    protected $signature = 'user:role {email} {role}';

    protected $description = 'Command description';

    public function __construct(RegisterService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): bool
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with email ' . $email);
            return false;
        }

        $user->changeRole($role);

        $this->info('Role is already assigned.');
        return true;
    }
}
