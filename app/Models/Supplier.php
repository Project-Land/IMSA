<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public static function getStats($standardId, $year)
    {
        $sup_total = Supplier::where('standard_id', $standardId)->whereYear('created_at', $year)->count();
        $sup_approved = Supplier::where('standard_id', $standardId)->whereYear('created_at', $year)->where('status', '1')->count();;

        if($sup_approved == 0){
            $sup_percentage = 0;
        }
        else{
            $sup_percentage = ($sup_approved / $sup_total) * 100;
        }

        return "Odobreno ".$sup_approved. " isporučilaca od ukupno ".$sup_total.", što čini ".round($sup_percentage)."%";
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
