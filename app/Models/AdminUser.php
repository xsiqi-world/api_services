<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Model
{

    protected $table = 'admin_user';
    public $timestamps = false;

    // 数据库主键
    protected $primaryKey = 'id';

    private static $instance;

    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }


}
