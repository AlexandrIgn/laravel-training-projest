<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Attribute;

class Category extends Model
{
    use NodeTrait;

    protected $table = 'advert_categories';

    public $timestamps = false;

    protected $fillable = [
        'name', 'slug', 'parent_id',
    ];

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id');
    }

    public function parentAttributes()
    {
        return $this->parent ? $this->parent->allAttributes() : [];

    }

    public function allAttributes()
    {
        return $this->parentAttributes() + $this->attributes()->orderBy('sort')->getModels();
    }
}
