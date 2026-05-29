<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MassagePackage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'staff_premium', 'reception_premium'];
}
