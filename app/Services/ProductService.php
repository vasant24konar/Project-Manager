<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function listProducts(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->productRepository->paginate($perPage, $search);
    }

    public function findProduct(int $id): Product
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct(array $validated): Product
    {
        $validated['created_by'] = Auth::id();

        $validated['status'] = Auth::user()->isAdmin()
            ? \App\Models\Product::STATUS_APPROVED
            : \App\Models\Product::STATUS_PENDING;

        if ($validated['status'] === \App\Models\Product::STATUS_APPROVED) {
            $validated['approved_by'] = Auth::id();
            $validated['approved_at'] = now();
        }

        return $this->productRepository->create($validated);
    }

    public function updateProduct(Product $product, array $validated): Product
    {
        if ($product->isRejected() && ! Auth::user()->isAdmin()) {
            $validated['status']           = Product::STATUS_PENDING;
            $validated['rejection_reason'] = null;
        }

        return $this->productRepository->update($product, $validated);
    }

    public function deleteProduct(Product $product): void
    {
        $this->productRepository->delete($product);
    }

    public function canModify(User $user, Product $product): bool
    {
        return $user->isAdmin() || $product->created_by === $user->id;
    }
}
