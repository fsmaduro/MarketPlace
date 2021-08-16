<?php

namespace App;

use App\Notifications\StoreReceiveNewOrder;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Slug;

class Store extends Model
{
    use Slug;

    protected $fillable = ['name','description','phone','mobile_phone','slug','logo'];

    public function user() {
        $this->belongsTo(User::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->belongsToMany(UserOrder::class, 'order_store', null, 'order_id');
    }

    public function notfyStoreOwners(array $storeId = [])
    {
        $stores = $this->whereIn('id',$storeId)->get();

        $stores->map(function($store) {
            return $store->user;
        })->each->notify(new StoreReceiveNewOrder());
    }
}
