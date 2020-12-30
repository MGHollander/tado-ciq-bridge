<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CleanUserSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up user sessions that haven\'t been updated for 6 months';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::where('updated_at', '<', now()->subMonths(6))->delete();
    }
}
