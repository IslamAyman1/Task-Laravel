<?php

namespace App\Console\Commands;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeletePost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force Delete To Soft Delete Post every 30 day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ThirtyDay = Carbon::now()->subDays(30);
        $post = Post::onlyTrashed()->where('deleted_at','<' ,$ThirtyDay)->forceDelete();
        $this->info('Trashed Post Deleted Successfully');
    }
}
