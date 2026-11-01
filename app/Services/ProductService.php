<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function createProduct(array $data, array $images = []): Product
    {
        return DB::transaction(function () use ($data, $images) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
            $product = Product::create($data);

            if (!empty($images)) {
                $this->uploadProductImages($product, $images);
            }

            return $product->fresh(['category', 'images', 'primaryImage']);
        });
    }

    public function updateProduct(Product $product, array $data, array $images = []): Product
    {
        return DB::transaction(function () use ($product, $data, $images) {
            if (isset($data['name']) && $data['name'] !== $product->name) {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
            }

            $product->update($data);

            if (!empty($images)) {
                $this->uploadProductImages($product, $images);
            }

            return $product->fresh(['category', 'images', 'primaryImage']);
        });
    }

    public function deleteProduct(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            return $product->delete();
        });
    }

    public function uploadProductImages(Product $product, array $files): void
    {
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($files as $index => $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->store('products', 'public');
                $isPrimary = (!$hasPrimary && $index === 0);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                ]);

                if ($isPrimary) {
                    $hasPrimary = true;
                }
            }
        }
    }

    public function deleteImage(ProductImage $image): bool
    {
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $wasPrimary = $image->is_primary;
        $productId = $image->product_id;
        $image->delete();

        if ($wasPrimary) {
            $nextImage = ProductImage::where('product_id', $productId)->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        return true;
    }

    public function setPrimaryImage(ProductImage $image): void
    {
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
    }

    public function updateStock(Product $product, int $quantity): Product
    {
        $product->update(['stock_quantity' => max(0, $quantity)]);
        return $product;
    }
}
