<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class SyncCommentsCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comments:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize comments_count field with actual comments data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting comments count synchronization...');

        $posts = Post::all();
        $updatedCount = 0;

        foreach ($posts as $post) {
            // Hitung jumlah comments yang sebenarnya dari tabel comments
            $actualCommentsCount = DB::table('comments')
                ->where('post_id', $post->id)
                ->count();

            // Update comments_count jika berbeda
            if ($post->comments_count !== $actualCommentsCount) {
                $oldCount = $post->comments_count;
                $post->update(['comments_count' => $actualCommentsCount]);
                $updatedCount++;
                $this->line("Post ID {$post->id}: Updated comments_count from {$oldCount} to {$actualCommentsCount}");
            }
        }

        $this->info("Synchronization completed! Updated {$updatedCount} posts.");

        return Command::SUCCESS;
    }
}
