<?php

namespace App\Models;

use App\Contracts\LogInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    static public function saveLog(LogInterface $log){
        self::create($log->formatted());
    }
}
