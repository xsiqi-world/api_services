<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
  /**
   * Undocumented function
   *
   * @param Request $request
   * @return void
   */
  public function index(Request $request)
  {
    Redis::set('name', 'Taylor');

    $values = Redis::lrange('names', 5, 10);
    $name = Redis::get('name');
    
    return $this->success((object)['query' => $request->query(), 'name' => $name]);
  }
}
