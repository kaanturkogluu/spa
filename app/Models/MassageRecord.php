<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MassageRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'staff_id',
        'massage_package_id',
        'duration_minutes',
        'payment_method',
        'base_price',
        'discount',
        'final_price',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function staff() { return $this->belongsTo(Staff::class); }
    public function package() { return $this->belongsTo(MassagePackage::class, 'massage_package_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
    public function deleter() { return $this->belongsTo(User::class, 'deleted_by'); }
}
