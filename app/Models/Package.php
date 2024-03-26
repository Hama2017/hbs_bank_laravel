<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'agios', 'limit_amount', 'agios_fees'];
    public function subscriptionPackages()
    {
        return $this->hasMany(SubscriptionPackage::class, 'id_package');
    }
}
