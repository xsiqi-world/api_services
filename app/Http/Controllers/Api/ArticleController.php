<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * 分类列表
     *
     * @return void
     */
    public function categoryList (Request $request) {
        $category = Category::getInstance()
            ->simplePaginate(15)
            ->toArray();

        $data = $category['data'];
        $data['total'] = $category['to'];
        return $this->success($data);
    }

    /**
     * 文章列表
     *
     * @return void
     */
    public function articleList () {
        $category = Article::getInstance()->select('category.name as category_name', 'category.icon', 'blog_article.title', 'blog_article.description', 'blog_article.access_num', DB::raw("date_format(from_unixtime(create_time),'%Y-%m-%d %H:%i:%s') as create_time"))
            ->leftJoin('blog_category as category', 'blog_article.category_id', '=', 'category.id')
            ->simplePaginate(15)
            ->toArray();

        $data = $category['data'];
        $data['total'] = $category['to'];
        return $this->success($data);
    }

    /**
     * 文章详情
     *
     * @return void
     */
    public function articleInfo (Request $request) {
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $articleInfo = Article::getInstance()->select('category.name as category_name', 'category.icon', 'blog_article.title', 'blog_article.description', 'blog_article.content', 'blog_article.access_num', DB::raw("date_format(from_unixtime(create_time),'%Y-%m-%d %H:%i:%s') as create_time"))
            ->leftJoin('blog_category as category', 'blog_article.category_id', '=', 'category.id')
            ->where('blog_article.id', $request['id'])
            ->first()
            ->toArray();

        return $this->success($articleInfo);
    }

    /**
     * 文章添加
     *
     * @return void
     */
    public function articleAdd (Request $request) {
        $this->validate($request, [
            'category_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            $articleId = Article::insertGetId([
                'title' => $request['title'],
                'description' => $request['description'],
                'content' => $request['content'],
                'category_id' => $request['category_id'],
                'create_time' => time()
            ]);
    
            return $this->success($articleId);
        } catch (\Exception $e) {
            info($e->getMessage());
            $this->fail();
        }
        
    }

    /**
     * 文章修改
     *
     * @return void
     */
    public function articleEdit (Request $request) {
        $this->validate($request, [
            'id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|integer',
        ]);

        try {
            $article = Article::getInstance()->find($request['id']);
            $article->title = $request['title'];
            $article->description = $request['description'];
            $article->content = $request['content'];
            $article->category_id = $request['category_id'];
            $article->save();

            return $this->success();
        } catch (\Exception $e) {
            info($e->getMessage());
            $this->fail();
        }
        
    }

}
