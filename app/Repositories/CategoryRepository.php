<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::ordered()->get();
    }

    public function active(): Collection
    {
        return Cache::tags(['categories'])->remember('active_categories', 3600, function () {
            return Category::active()
                ->ordered()
                ->withCount('activeProducts')
                ->get();
        });
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }

    public function create(array $data): Category
    {
        Cache::tags(['categories'])->flush();
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        Cache::tags(['categories'])->flush();
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        Cache::tags(['categories'])->flush();
        return $category->delete();
    }

    public function getWithProductCount(): Collection
    {
        return Category::active()
            ->withCount('activeProducts')
            ->ordered()
            ->get();
    }
}
