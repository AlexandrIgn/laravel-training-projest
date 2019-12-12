<?php

namespace App\Console\Commands\User;

use Illuminate\Console\Command;
use App\Services\Auth\RegisterService;
use App\User;

class VerifyCommand extends Command
{
    private $service;

    protected $signature = 'user:verify {email}';

    protected $description = 'Command description';

    public function __construct(RegisterService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): bool
    {
        $email = $this->argument('email');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with email ' . $email);
            return false;
        }

        $this->service->verify($user->id);

        $this->info('User is successfully verified');
        return true;
    }
}
