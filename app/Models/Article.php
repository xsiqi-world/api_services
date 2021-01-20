<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'blog_article';
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
