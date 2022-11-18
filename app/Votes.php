<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Article;
use DB;

class Votes extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['vote', 'article_id', 'user_id'];
    protected $table = 'votes_logs';

    /**
     * The query returns if the user voted the article.
     *
     */

    static function ifvoted(array $r)
    {
            if (count(Votes::queryGetVotes()->where(['user_id' => $r['user_id'], 'article_id' => $r['article_id']])->get()) > 0) {
                return true;
            } else {
                return false;
            }
    }

     /**
     * The base start query to votes_logs.
     *
     */
    
    static function queryGetVotes()
    {
        return DB::table('votes_logs');
    }
}
