<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drg extends Model
{
    use HasFactory;

    protected $fillable = ['drg_name','file_path', 'material', 'thickness','sheet_qty','sheet_qty_done', 'unit_time', 'real_full_time', 'statu','comment'];

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

    public function GetPrettyCreatedAttribute()
    {
        return date('d F Y', strtotime($this->created_at));
    }
}
