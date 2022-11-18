<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Validator;
use App\Article;
use App\ArticleCategories;
use App\Votes;

class ArticleController extends Controller
{
     /**
     * Return all articles with sorts
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request)
    {
        return response()->json(Article::getallArticles($request->all()), 200);
    }

     /**
     * Return all user articles
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */

    public function get_my_articles(Request $request, Article $article)
    {
        return response()->json($article->getallMyArticles($request->user()), 200);
    }

     /**
     * Return article by id
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Article $article
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function show(Article $article, int $id)
    {
        return response()->json($article->getArticle($id), 200);
    }

    /**
     * Adding a new article
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Article $article
     * @param \App\ArticleCategories $articlecategories
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request, Article $article, ArticleCategories $articlecategories)
    {

        // Validator of input params
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'category' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        // Send article data to db
        $request->merge(['user_id' => $request->user()->id]);
        $article = $article->create($request->all());

        // If article created in db, add article to the category
        if (isset($article->id)) {
            $articlecategories->create(['article_id'=> $article->id, 'category_id' => $request->get('category')]);
            $article->category = $request->get('category');
        }

        return response()->json($article, 201);
    }

    /**
     * Update existing article
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Article $article
     * @param \App\ArticleCategories $articlecategories
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request, Article $article, ArticleCategories $articlecategories)
    {

        // Validator of input params
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'category' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Check if this user created this article
        if ($request->user()->id != $article->user_id) {
            return response()->json(['error' => 'Unauthenticated'], 400);
        }

        // Update article data
        $article->update($request->all());

        // Update article category
        $articlecategories->updateArticleCategory(['article_id'=> $article->id, 'category_id' => $request->get('category')]);

        return response()->json($article->getArticle($article->id), 200);
    }

    /**
     * The function that implements vote up and down of the article
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function vote(Request $request)
    {

        // Validator of input params
        $validator = Validator::make($request->all(), [
            'vote' => 'required|bool',
            'article_id' => 'required|int',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        
        // Check if user already voted this article
        $votes = Votes::ifvoted(['user_id'=>$request->user()->id, 'article_id'=> $request->get('article_id')]);


        if ($votes !== true) {
            // Add to the votes_logs what this user voted in the article
            $request->merge(['user_id' => $request->user()->id]);
            Votes::create($request->all());


            if ($request->get('vote') == 1) {
                // Vote up
                Article::upVote($request->get('article_id'));
            } else {
                // Vote down
                Article::downVote($request->get('article_id'));
            }
            $votes = true;
        }


        return response()->json($votes, 200);
    }

     /**
     * Delete article
     *
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */

    public function delete(Article $article)
    {
        $article->delete();
        return response()->json(null, 204);
    }
    
}
