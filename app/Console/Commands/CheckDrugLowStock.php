<?php
namespace App\Console\Commands;

use App\Models\Product;
use App\Notifications\DrugLowStockAlert;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckDrugLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drugs:check-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check drug stock and send alerts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today    = Carbon::today();
        $products = Product::where('quantity', '<=', 50)
            ->where('expiry', '>=', $today)
            ->get();

        foreach ($products as $product) {
            Notification::route('mail', 'ronocollins2000@gmail.com')
                ->notify(new DrugLowStockAlert($product, $product->quantity));
            $this->info("Stock alert sent for {$product->name} (Quantity is {$product->quantity} days)");
        }
        $this->info('Drug stock check completed.');
    }
}
