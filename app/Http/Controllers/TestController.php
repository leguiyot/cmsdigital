<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        try {
            // Probar consulta simple
            $articles = Article::all();
            
            // Probar relación author
            $article = Article::first();
            if ($article) {
                $author = $article->author;
            }
            
            return response()->json([
                'status' => 'success',
                'articles_count' => $articles->count(),
                'first_article_has_author' => $article ? ($article->author ? true : false) : false
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }
}
