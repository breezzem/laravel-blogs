<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UsersAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display a table listing all users with columns: ID, Email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = User::all(['id', 'email'])->toArray();

        $this->table(['ID', 'Email'], $data);

        return Command::SUCCESS;
    }
}
