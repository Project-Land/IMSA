<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSatisfaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = "customer_satisfaction";

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

  

    public function average()
    {
        $sum = 0;
        $count = 0;
        for($i=1; $i<=10; $i++){
            if($this->{'col'.$i} != null){
                $sum += $this->{'col'.$i};
                $count++;
            }
        }
        return round($sum/$count, 1);
    }
}
