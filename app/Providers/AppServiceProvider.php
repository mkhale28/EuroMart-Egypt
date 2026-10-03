<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::shouldBeStrict(!App::environment('production'));
        Model::preventLazyLoading(!App::environment('production'));

        Paginator::useTailwind();

        if (App::environment('production')) {
            URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\Blade::directive('money', function (string $amount) {
            return "<?php echo number_format((float) $amount, 2) . ' EGP'; ?>";
        });
    }
}
