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

    /**
     * The attributes for date created and updated at.
     *
     * @var array
     */
    const CREATED_AT = 'user_login_creation_date';
    const UPDATED_AT = 'user_login_update_date';
    const DELETED_AT = 'user_login_deleted_at';


}