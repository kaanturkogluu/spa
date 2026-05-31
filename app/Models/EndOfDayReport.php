<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EndOfDayReport extends Model
{
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
