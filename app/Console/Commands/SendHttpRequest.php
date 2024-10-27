<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class SendHttpRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send http request every 6 hours and log result';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $result =  Http::get('https://randomuser.me/api/');
        return response()->json($result);
    }
}
