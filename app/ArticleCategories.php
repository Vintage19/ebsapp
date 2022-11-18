<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ArticleCategories extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['article_id', 'category_id'];
    
    /**
     * Update Article category_id.
     *
     */
    
    static function updateArticleCategory($data)
    {
        return ArticleCategories::queryGetArticleCategory($data['article_id'])->update(array('category_id' => $data['category_id']));
    }

    /**
     * The base query to get Article category_id.
     *
     */

    static function queryGetArticleCategory($article_id)
    {
        return DB::table('article_categories')->where('article_id', '=', $article_id);
    }
}
