<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RuleController extends Controller
{
    /**
     * 权限列表
     * @return void
     */
    public function ruleList (Request $request) {
          $list = Rule::getInstance()
              ->get()
              ->toArray();

          return $this->success($list);
    }

    /**
     * 权限菜单下的权限
     * @param Request $request
     * @return \App\Http\Controllers\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
      public function ruleAuthFindById (Request $request) {
          $this->validate($request, [
              'id' => 'required|integer',
          ]);

          $menuInfo = Rule::where('id', $request['id'])->where('status', '1')->first();
          $authList = Rule::where('pid', $request['id'])->where('status', '1')->get();

          return $this->success([
              'menuInfo' => $menuInfo,
              'authList' => $authList
          ]);
      }

    /**
     * 添加权限
     * @param Request $request
     * @return \App\Http\Controllers\JsonResponse
     * @throws ValidationException
     */
    public function ruleSave (Request $request) {

          $this->validate($request, [
              'title' => 'required|string',
              'url' => 'required|string',
              'name' => 'required|string',
              'pid' => 'required|integer',
              'is_menu' => 'required|integer'
          ]);

          DB::connection('mysql')->beginTransaction();
          try {
              $insertId = Rule::insertGetId([
                  'title' => $request['title'],
                  'url' => $request['url'],
                  'name' => $request['name'],
                  'pid' => $request['pid'],
                  'create_time' => time(),
                  'is_menu' => $request['is_menu'],
              ]);

              DB::connection('mysql')->commit();
              return $this->success($insertId);
          } catch (\Exception $e) {
              info($e->getMessage());
              DB::connection('mysql')->rollback();// 事务回滚
              return $this->fail();
          }
    }

    /**
     * 更新权限
     * @param Request $request
     * @return \App\Http\Controllers\JsonResponse
     * @throws ValidationException
     */
      public function ruleUpdate (Request $request) {
          $this->validate($request, [
              'id' => 'required|integer',
              'title' => 'required|string',
              'url' => 'required|string',
              'name' => 'required|string',
          ]);

          DB::connection('mysql')->beginTransaction();
          try {
              $article = Rule::getInstance()->find($request['id']);
              $article->title = $request['title'];
              $article->url = $request['url'];
              $article->name = $request['name'];
              $article->save();

              DB::connection('mysql')->commit();
              return $this->success();
          } catch (\Exception $e) {
              info($e->getMessage());
              DB::connection('mysql')->rollback();// 事务回滚
              return $this->fail();
          }
      }

}
