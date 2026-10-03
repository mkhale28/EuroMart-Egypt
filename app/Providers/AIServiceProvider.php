<?php

namespace App\Providers;

use App\Services\AI\AISearchService;
use App\Services\AI\Contracts\AISearchServiceInterface;
use App\Services\AI\Contracts\RecommendationServiceInterface;
use App\Services\AI\RecommendationService;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RecommendationServiceInterface::class, RecommendationService::class);
        $this->app->bind(AISearchServiceInterface::class, AISearchService::class);
    }

    public function boot(): void
    {
        //
    }
}
