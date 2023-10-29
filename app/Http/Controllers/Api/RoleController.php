<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * 角色列表
     * @return \App\Http\Controllers\JsonResponse
     */
    public function roleList (Request $request) {
          $category = Role::getInstance()
              ->simplePaginate(20)
              ->toArray();

          $data['data'] = $category['data'];
          $data['total'] = $category['to'];
          return $this->success($data);
    }


    /**
     * 添加角色
     * @param Request $request
     * @return \App\Http\Controllers\JsonResponse
     * @throws ValidationException
     */
    public function roleSave (Request $request) {

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
     * 更新角色
     * @param Request $request
     * @return \App\Http\Controllers\JsonResponse
     * @throws ValidationException
     */
    public function roleUpdate (Request $request) {
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

    /**
     * 获取角色下的权限
     * @param Request $request
     * @return \App\Http\Controllers\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getRoleRuleList (Request $request) {
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $list = Role::getInstance()
            ->where('id', $request['id'])
            ->where('status', 1)
            ->first()
            ->toArray();

        return $this->success($list);
    }

}
