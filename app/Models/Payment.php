<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'name',
        'last_name',
        'amount',
        'description',
        'quotation_number',
        'email',
        'brand',
        'url',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
