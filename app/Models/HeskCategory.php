<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class HeskCategory extends Model
{
    protected $connection = 'hesk';

    protected $table = 'categories';

    public $timestamps = false;

    protected $guarded = [];

    public function tickets(): HasMany
    {
        return $this->hasMany(HeskTicket::class, 'category');
    }
}
