<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ArticleCategory extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    const COLOR_MAP = [
    	'red' => '红色',
    	'blue' => '蓝色',
    	'yellow' => '黄色',
    	'yellowgreen' => '青色',
    	'orange' => '橙色',
    	'purple' => '紫色',
    	'green' => '绿色',
    	'pink' => '粉色'
    ];

    public function article()
    {
        return $this->hasMany(Article::class, 'article_category_id', 'id');
    }

    
}
