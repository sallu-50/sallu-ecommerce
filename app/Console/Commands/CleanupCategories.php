<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CleanupCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup duplicate categories and trim whitespace';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Cleaning up categories...');

        // Trim whitespace from all category names
        $categories = Category::all();
        foreach ($categories as $category) {
            $category->update(['name' => trim($category->name)]);
        }

        $this->info('Whitespace trimmed from category names.');

        // Get unique category names
        $uniqueCategoryNames = Category::distinct()->pluck('name');

        foreach ($uniqueCategoryNames as $name) {
            $categories = Category::where('name', $name)->orderBy('id')->get();
            if ($categories->count() > 1) {
                $firstCategory = $categories->first();
                $otherCategories = $categories->slice(1);

                $this->info("Found " . $otherCategories->count() . " duplicates for category '{$name}'. Merging them.");

                foreach ($otherCategories as $otherCategory) {
                    Product::where('category_id', $otherCategory->id)->update(['category_id' => $firstCategory->id]);
                    $otherCategory->delete();
                }
            }
        }

        $this->info('Categories cleaned up successfully.');

        return 0;
    }
}
