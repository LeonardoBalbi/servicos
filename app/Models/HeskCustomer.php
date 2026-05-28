<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class HeskCustomer extends Model
{
    protected $connection = 'hesk';

    protected $table = 'customers';

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'pass',
        'mfa_secret',
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(HeskReply::class, 'customer_id');
    }
}
