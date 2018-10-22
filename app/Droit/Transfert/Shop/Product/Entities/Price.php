<?php namespace App\Droit\Shop\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model{

    use SoftDeletes;

    protected $table = 'shop_prices';

    protected $dates = ['deleted_at'];

    protected $fillable = ['product_id', 'price_net', 'price_gross'];
    /**
     * Set timestamps off
     */
    public $timestamps = false;

    public function getPriceConvertAttribute()
    {
        $prix = $this->price_net/100;
        $prix = number_format($prix, 2, '.', ' ');

        return $prix;
    }

    public function setPriceNetAttribute($value)
    {
        $this->attributes['price_net'] = $value * 100;
    }

    public function setPriceGrossAttribute($value)
    {
        $this->attributes['price_gross'] = $value * 100;
    }
}