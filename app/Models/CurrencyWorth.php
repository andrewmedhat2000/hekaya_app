<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyWorth extends Model
{
    //
    protected $table = 'currency_worths';

    protected $fillable = [
        'price', 'diamond', 'gem', 'charisma', 'contribution',
    ];


    public static function getCharismaRatio()
    {
        $values = self::firstOr(function(){
            return self::make(['diamond' => 1, 'charisma' => 1]);
        });

        return $values->charisma / ($values->diamond ?: 1);
    }

    public static function getContributionRatio()
    {
        $values = self::firstOr(function(){
            return self::make(['diamond' => 1, 'contribution' => 1]);
        });

        return $values->contribution / ($values->diamond ?: 1);
    }
}
