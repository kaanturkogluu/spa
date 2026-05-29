<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MassageRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_number',
        'staff_id',
        'staff_id_2',
        'massage_package_id',
        'duration_minutes',
        'start_time',
        'end_time',
        'payment_method',
        'base_price',
        'discount',
        'final_price',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function staff2()
    {
        return $this->belongsTo(Staff::class, 'staff_id_2');
    }

    public function package() { return $this->belongsTo(MassagePackage::class, 'massage_package_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
    public function deleter() { return $this->belongsTo(User::class, 'deleted_by'); }
}
