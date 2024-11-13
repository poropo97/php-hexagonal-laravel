<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Bid extends Model
{
    // Define the relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship to Product if needed
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
