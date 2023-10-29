<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    //
    protected $table = 'admin_rule';
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
