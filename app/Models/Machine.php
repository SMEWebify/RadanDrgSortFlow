<?php

namespace App\Models;

use App\Models\Drg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'type', 'capacity', 'hourly_rate', 'zone_x', 'zone_y', 'image'
    ];

    public function drgs()
    {
        return $this->hasMany(Drg::class)->orderBy('statu');
    }
}
