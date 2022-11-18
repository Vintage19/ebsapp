<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;


class Categories extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['name', 'url'];

    /**
     * Get first 5 categories, sorted by articles count DESC
     *
     */

    static function getallCategories()
    {

        return Categories::queryGetCategories()->groupBy('id', 'name', 'url')->orderBy('article_count', 'DESC')->having('article_count', '>=', 2)->limit(5)->get();
    }

    /**
     * The base query for each function to get categories, with count of articles in category
     * and the rate of the votes of articles.
     *
     */

    static function queryGetCategories()
    {
        return Categories::tableGetCategories()->selectRaw('categories.id, categories.name, categories.url, COUNT(*) as article_count, SUM(articles.vote) as votes')->
                                        leftJoin('article_categories', 'article_categories.category_id', '=', 'categories.id')->
                                        leftJoin('articles', 'article_categories.article_id', '=', 'articles.id');
    }
    
    /**
     * The base start query to get Categories.
     *
     */

    static function tableGetCategories()
    {
        return DB::table('categories');
    }
}
