<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class LastLoginModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'last_login';

    protected $fillable = ['user_id', 'last_login']; 


}