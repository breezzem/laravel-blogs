<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;

class BlogsDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blogs:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a blog by ID with confirmation prompt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        
        $blog = Blog::find($id);
        
        if (!$blog) {
            $this->error("Blog with ID {$id} not found.");
            return Command::FAILURE;
        }

        if ($this->confirm("Do you really want to delete Blog {$id}?", false)) {
            $blog->delete();
            $this->info("Blog {$id} deleted successfully");
            return Command::SUCCESS;
        }

        $this->info('Deletion cancelled.');
        return Command::SUCCESS;
    }
}
