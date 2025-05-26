<?php
namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\ExpiryAlert;
use Illuminate\Console\Command;
use App\Notifications\DrugExpiryAlert;
use Illuminate\Support\Facades\Notification;

class CheckDrugExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drugs:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check drugs expiry and send alerts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today      = Carbon::today();
        $thresholds = [30, 60, 90];

        foreach ($thresholds as $days) {
            $targetDate    = $today->copy()->addDays($days)->startOfDay();
            $targetDateEnd = $targetDate->copy()->endOfDay();

            // Find products expiring within the threshold window
            $products = Product::where('deleted', false)
                ->where('quantity', '>', 0)
                ->whereBetween('expiry', [$today, $targetDateEnd])
                ->whereDoesntHave('expiryAlerts', function ($query) use ($days) {
                    $query->where('days_to_expiry', $days);
                })
                ->get();

            foreach ($products as $product) {
                // Calculate exact days to expiry
                $daysToExpiry = $product->expiry->diffInDays($today);

                // Only send alert if the product expires close to the threshold
                if ($daysToExpiry <= $days && $daysToExpiry > ($days - 30)) {
                                                                     // Send notification to admin (or relevant users)
                    Notification::route('mail', 'ronocollins2000@gmail.com') // Replace with actual recipient
                        ->notify(new DrugExpiryAlert($product, $days));

                    // Log the alert
                    ExpiryAlert::create([
                        'product_id'     => $product->id,
                        'days_to_expiry' => $days,
                        'alerted_at'     => now(),
                    ]);

                    $this->info("Alert sent for {$product->name} (Expires in {$daysToExpiry} days)");
                }
            }
        }

        $this->info('Drug expiry check completed.');
    }
}
