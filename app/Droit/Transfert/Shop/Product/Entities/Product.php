<?php namespace App\Droit\Transfert\Shop\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model{

    use SoftDeletes;

    protected $table = 'shop_products';

    protected $dates = ['deleted_at','edition_at'];

    protected $fillable = ['title', 'teaser', 'image', 'description', 'weight','price', 'sku', 'is_downloadable','download_link','hidden','url','rang','pages','reliure','edition_at','format','notify_url'];

}