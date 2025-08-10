<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Import BelongsTo

class Sales_Item extends Model
{
    protected $table = 'sales_items';

    protected $guarded = []; //lebih simpel

    /**
     * Get the product that the sales item belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * NOTE: This user relationship seems incomplete and is not currently used.
     * Get the user that owns the Sales_item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        // This line is a placeholder and would need correct keys to function
        return $this->belongsTo(User::class, 'foreign_key', 'other_key');
    }
}
