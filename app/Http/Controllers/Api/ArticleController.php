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

        $data['data'] = $category['data'];
        $data['total'] = $category['to'];
        return $this->success($data);
    }

    /**
     * 文章列表
     *
     * @return void
     */
    public function articleList (Request $request) {
        $this->validate($request, [
            'category_id' => 'nullable|integer'
        ]);
        $categoryId = $request['category_id'] ?? '';

        $category = Article::getInstance()->select('blog_article.id', 'category.name as category_name', 'category.icon', 'blog_article.title', 'blog_article.description', 'blog_article.access_num', DB::raw("date_format(from_unixtime(create_time),'%Y-%m-%d %H:%i:%s') as create_time"))
            ->leftJoin('blog_category as category', 'blog_article.category_id', '=', 'category.id')
            ->when(!empty($categoryId), function ($query) use ($categoryId) {
                return $query->where('blog_article.category_id', $categoryId);
            })
            ->where('blog_article.is_delete', 0)
            ->simplePaginate(15)
            ->toArray();

        $data['data'] = $category['data'];
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

        $articleInfo = Article::getInstance()->select('category.name as category_name', 'category.id as category_id', 'category.icon', 'blog_article.title', 'blog_article.description', 'blog_article.content', 'blog_article.access_num', DB::raw("date_format(from_unixtime(create_time),'%Y-%m-%d %H:%i:%s') as create_time"))
            ->leftJoin('blog_category as category', 'blog_article.category_id', '=', 'category.id')
            ->where('blog_article.id', $request['id'])
            ->first();

        return $this->success($articleInfo);
    }

    /**
     * 文章添加
     *
     * @return void
     */
    public function articleAdd (Request $request) {
        $this->validate($request, [
            'category_id' => 'required',
            'title' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        DB::connection('mysql')->beginTransaction();
        try {
            $articleId = Article::insertGetId([
                'title' => $request['title'],
                'description' => $request['description'],
                'content' => $request['content'],
                'category_id' => $request['category_id'],
                'create_time' => time(),
                'access_num' => 0
            ]);
    
            DB::connection('mysql')->commit();
            return $this->success($articleId);
        } catch (\Exception $e) {
            info($e->getMessage());
            DB::connection('mysql')->rollback();// 事务回滚
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

        DB::connection('mysql')->beginTransaction();
        try {
            $article = Article::getInstance()->find($request['id']);
            $article->title = $request['title'];
            $article->description = $request['description'];
            $article->content = $request['content'];
            $article->category_id = $request['category_id'];
            $article->save();

            DB::connection('mysql')->commit();
            return $this->success();
        } catch (\Exception $e) {
            info($e->getMessage());
            DB::connection('mysql')->rollback();// 事务回滚
            $this->fail();
        }
        
    }

    // 删除文章
    public function deleteArticle (Request $request) {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);

        DB::connection('mysql')->beginTransaction();
        try {
            // Article::where('id', $request['id'])
            //     ->delete();
            $model = Article::getInstance()->find($request['id']);
            $model->is_delete = 1;
            $model->save();

            DB::connection('mysql')->commit();
            return $this->success();
        } catch (\Exception $e) {
            info($e->getMessage());
            DB::connection('mysql')->rollback();// 事务回滚
            $this->fail();
        }

    }

}
