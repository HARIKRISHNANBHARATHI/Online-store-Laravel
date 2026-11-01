<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;

class WishlistService
{
    public function getWishlist(User $user): Wishlist
    {
        return Wishlist::firstOrCreate(['user_id' => $user->id])->load(['items.product.primaryImage']);
    }

    public function toggleWishlist(User $user, Product $product): array
    {
        $wishlist = Wishlist::firstOrCreate(['user_id' => $user->id]);

        $item = WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->delete();
            return ['status' => 'removed', 'message' => 'Product removed from wishlist.'];
        }

        WishlistItem::create([
            'wishlist_id' => $wishlist->id,
            'product_id' => $product->id,
        ]);

        return ['status' => 'added', 'message' => 'Product added to wishlist.'];
    }

    public function removeItem(User $user, int $productId): bool
    {
        $wishlist = Wishlist::where('user_id', $user->id)->first();
        if (!$wishlist) {
            return false;
        }

        return WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('product_id', $productId)
            ->delete() > 0;
    }
}
