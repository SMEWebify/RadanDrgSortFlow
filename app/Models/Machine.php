<?php

namespace App\Models;

use App\Models\Drg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'type', 'capacity', 'hourly_rate', 'zone_x', 'zone_y', 'image', 'color'
    ];

    public function drgs()
    {
        return $this->hasMany(Drg::class)->orderBy('statu');
    }

    // Fonction pour activer la machine
    public function activate()
    {
        $this->is_active = true;
        $this->save();
    }

    // Fonction pour désactiver la machine
    public function deactivate()
    {
        $this->is_active = false;
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
