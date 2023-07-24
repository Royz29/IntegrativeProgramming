<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DeleteAccountCommand extends Command
{
    protected $signature = 'user:delete {id}';
    protected $description = 'Delete user account';

    protected $userId;

    public function setArguments($arguments)
    {
        $this->userId = $arguments['id'];
        return $this;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::find($this->userId);
        if (!$user) {
            $this->error('User not found!');
            return;
        }

        $user->delete();
        if ($this->output) {
            $this->output->writeln('User account deleted successfully!');
        } else {
            echo 'User account deleted successfully!';
        }
    }
}
