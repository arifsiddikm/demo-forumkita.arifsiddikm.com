<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Thread;
use Illuminate\Support\Facades\DB;

class SyncRepliesCount extends Command
{
    protected $signature   = 'forum:sync-counts';
    protected $description = 'Sync replies_count for all threads based on actual reply count';

    public function handle(): int
    {
        $this->info('Syncing replies_count...');

        DB::statement('
            UPDATE threads
            SET replies_count = (
                SELECT COUNT(*) FROM replies WHERE replies.thread_id = threads.id
            )
        ');

        $this->info('✅ Done! All thread reply counts synced.');
        return 0;
    }
}
