<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TerminateExpiredApiKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terminate:expired_api_keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(\App\Services\TerminateExpiredApiKeys $terminateExpiredApiKeys)
    {
        $terminateExpiredApiKeys->terminateKeys();
        return 0;
    }
}
