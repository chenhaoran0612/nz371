<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Article extends Model
{
    use SoftDeletes;
    protected $guarded = [];
	
	const STATUS_MAP = [
		'pending' => '未发布',
		'publish' => '已发布'
	];

	public function getCategoryNameAttribute(){
		if(empty($this->article_category_id)){
			return '未设置分类';
		}
		return ArticleCategory::where('id' , $this->article_category_id)->value('category_name');
	}

	public function getStatusValueAttribute()
	{
		return self::STATUS_MAP[$this->status];
	}

	public function getBackgroundColorAttribue()
	{
		if(empty($this->article_category_id)){
			return '#333';
		}
		return ArticleCategory::where('id' , $this->article_category_id)->value('content_color');
	}
}
