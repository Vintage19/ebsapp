<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['title', 'body', 'user_id', 'vote'];

    /**
     * Get filtered articles.
     *
     */

    static function getallArticles(array $data)
    {
        $query = Article::queryGetArticles();

        // if isset filter by query 
        if (isset($data['query'])) {
            $query = Article::getsortArticlesByQuery($data['query']); 
        }

        // if isset filter by category_id 
        if (isset($data['cat_id'])) {
            $query = Article::getsortArticlesByCategory_id($data['cat_id']); 
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(2);
    }

    /**
     * Filter articles by query in title or body.
     *
     */

    static function getsortArticlesByQuery($query)
    {
        return Article::queryGetArticles()->where('articles.title', 'LIKE', '%'.$query.'%')->orWhere('articles.body', 'LIKE', '%'.$query.'%');
    }

    /**
     * Filter articles by category_id.
     *
     */
    
    static function getsortArticlesByCategory_id($cat_id)
    {
        return Article::queryGetArticles()->where('categories.id', '=', $cat_id);
    }

    /**
     * Get articles by user_id.
     *
     */

    static function getallMyArticles($auth)
    {
        return Article::queryGetArticles()->where('articles.user_id', '=', $auth->id)->get();
    }

    /**
     * Get article by id.
     *
     */

    static function getArticle($id)
    {
        return Article::queryGetArticles()->where('articles.id', '=', $id)->get();
    }

    /**
     * Vote up for article.
     *
     */

    static function upVote(int $article_id)
    {
        return Article::tableGetArticles()->where('id', '=', $article_id)->increment('vote');
    }

    /**
     * Vote down for article.
     *
     */

    static function downVote(int $article_id)
    {
        return Article::tableGetArticles()->where('id', '=', $article_id)->decrement('vote');
    }

    /**
     * The base query for each function.
     *
     */
    
    static function queryGetArticles()
    {
        return Article::tableGetArticles()->select(array('articles.*','article_categories.category_id AS category_id', 'categories.name AS category_name'))->
                                        leftJoin('article_categories', 'article_categories.article_id', '=', 'articles.id')->
                                        leftJoin('categories', 'article_categories.category_id', '=', 'categories.id');
    }

    /**
     * The base start query.
     *
     */

    static function tableGetArticles()
    {
        return DB::table('articles');
    }
    
}
