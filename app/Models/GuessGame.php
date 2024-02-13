<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuessGame extends Model
{

    protected $table = 'guess_games';

    protected $fillable = [
        'room_id',
        'challenger_id','challenger_shape_id',
        'challenged_id','challenged_shape_id',
        'package_id', 'winner_id',
        'created_at', 'updated_at',
    ];


    public function challenger(){
        return $this->belongsTo(User::class, 'challenger_id');
    }
    public function challenger_shape(){
        return $this->belongsTo(GuessShape::class, 'challenger_shape_id');
    }


    public function challenged(){
        return $this->belongsTo(User::class, 'challenged_id');
    }
    public function challenged_shape(){
        return $this->belongsTo(GuessShape::class, 'challenged_shape_id');
    }


    public function package(){
        return $this->belongsTo(GuessPackage::class, 'package_id');
    }



}
