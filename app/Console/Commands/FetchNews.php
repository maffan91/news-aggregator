<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Services\NewsAggregatorService;
use Illuminate\Console\Command;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command would fetch and store news articles';

    protected NewsAggregatorService $newsAggregatorService;

    public function __construct(NewsAggregatorService $newsAggregatorService)
    {
        parent::__construct();
        $this->newsAggregatorService = $newsAggregatorService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $this->info("Fetching articles for category: {$category->name}");
            $this->newsAggregatorService->fetch($category);
        }
        $this->info('News articles fetched successfully');
    }
}
