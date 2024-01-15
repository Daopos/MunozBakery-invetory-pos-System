<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use HasFactory;

    protected $fillable = ['qty', 'used_stock'];

    public function usedStock()
    {
        return $this->hasMany(UsedStock::class, 'stock_id');
    }
}