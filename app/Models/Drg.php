<?php

namespace App\Models;

use App\Models\Machine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Drg extends Model
{
    use HasFactory;

    protected $fillable = ['drg_name','file_path', 'material', 'thickness','sheet_qty','sheet_qty_done', 'unit_time', 'real_full_time', 'statu', 'machine_id', 'comment'];

    public function getStatuLabel()
    {
        switch ($this->statu) {
            case 1:
                return ['text' => 'A planifier', 'class' => 'badge badge-warning'];
            case 2:
                return ['text' => 'Planifier', 'class' => 'badge badge-primary'];
            case 3:
                return ['text' => 'En cours', 'class' => 'badge badge-info'];
            case 4:
                return ['text' => 'A refaire', 'class' => 'badge badge-danger'];
            case 5:
                return ['text' => 'Terminer', 'class' => 'badge badge-success'];
            case 6:
                return ['text' => 'Supprimer', 'class' => 'badge badge-secondary'];
            case 7:
                return ['text' => 'Stopper', 'class' => 'badge badge-dark'];
            default:
                return ['text' => 'Inconnu', 'class' => 'badge badge-light'];
        }
    }

    public function TotalTime()
    {
        return round($this->unit_time*$this->sheet_qty,2);
    }

    public function RemaningTotalTime()
    {
        return round($this->TotalTime()-$this->real_full_time,2);
    }

    public function Advencemnt()
    {
        return round($this->real_full_time/$this->TotalTime()*100,2);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getMachineNameAttribute()
    {
        return $this->machine ? $this->machine->name : 'Aucune machine';
    }

    // Méthode pour récupérer la couleur de la machine ou gris par défaut
    public function getMachineColor()
    {
        return $this->machine ? $this->machine->color ?? '#808080' : '#808080';  // Gris par défaut
    }

    public function GetPrettyCreatedAttribute()
    {
        return date('d F Y', strtotime($this->created_at));
    }
}
