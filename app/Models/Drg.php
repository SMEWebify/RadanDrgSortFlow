<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drg extends Model
{
    use HasFactory;

    protected $fillable = ['drg_name','file_path', 'material', 'thickness','sheet_qty','sheet_qty_done', 'unit_time', 'statu','comment'];

    public function TotalTime()
    {
        return round($this->unit_time*$this->sheet_qty,2);
    }

    public function RemaningTotalTime()
    {
        return round($this->unit_time*($this->sheet_qty-$this->sheet_qty_done),2);
    }

    public function GetPrettyCreatedAttribute()
    {
        return date('d F Y', strtotime($this->created_at));
    }
}
