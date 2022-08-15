<?php

namespace App\Http\Controllers\Backoffice\Bot;

use App\Models\MenuBot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class WoowaBotController extends Controller
{
    public function index(MenuBot $menuBot, Request $request)
    {
        $current = $menuBot->newModelQuery()->when($request->filled('id'), function ($menu) use ($request) {
            $menu->where('id', $request->get('id'));
        })->with(['keywords', 'parent'])->first();
        $childs = $current->childs;
        return view('back-office.woowa.index', compact('current', 'childs'));
    }

    public function update(MenuBot $menuBot, Request $request)
    {

        $rules = [
            'slug' => 'required|unique:menu_bots,slug,' . $request->id,
            'content' => 'required'
        ];
        $this->validate($request, $rules);
        $menu = $menuBot->newModelQuery()->when($request->filled('id'), function ($m) use ($request) {
            $m->where('id', $request->get('id'));
        })->first();
        $check = $menuBot
            ->newModelQuery()
            ->has('parent')
            ->whereHas('keywords', function ($k) use ($request) {
                $k->whereIn('keyword', array_filter($request->keywords));
            })
            ->whereHas('parent', function ($parent) use ($menu) {
                $parent->where('id', $menu->parent_id);
            })
            ->whereKeyNot($menu->id)->first();
        if ($check) {
            return apiResponse(422, 'No valid');
        }
        if ($menu->slug != 'contact-cs') {
            $menu->slug = Str::slug($request->slug);
        }
        $menu->content = $request->input('content');
        $menu->save();
        $keys = [];
        foreach (array_filter($request->keywords) as $item) {
            $keys[] = [
                'keyword' => $item
            ];
        }
        $menu->keywords()->delete();
        if (count($keys) > 0):
            $menu->keywords()->createMany($keys);
        endif;
        return apiResponse(200, 'OK', 'OK');

    }

    public function add(MenuBot $menuBot, Request $request)
    {
        if ($parent = $menuBot->newModelQuery()->find($request->get('parent'))):
            return view('back-office.woowa.add', compact('parent'));
        endif;
    }

    public function save(MenuBot $menuBot, Request $request)
    {
        $parent = MenuBot::where('id',$request->get('parent'))->first();
        if (!$parent) {
            return apiResponse(404,'Not found');
        }
        $request->merge(['slug' => Str::slug($request->input('slug'))]);
        $rules = [
            'slug' => 'required|unique:menu_bots,slug',
            'content' => 'required'
        ];
        $this->validate($request, $rules);
        $check = $menuBot
            ->newModelQuery()
            ->has('parent')
            ->whereHas('keywords', function ($k) use ($request) {
                $k->whereIn('keyword', array_filter(!is_array($request->keywords)?[]:$request->keywords));
            })
            ->whereHas('parent', function ($p) use ($parent) {
                $p->where('id', $parent->id);
            })->first();
        if ($check) {
            return apiResponse(422, 'No valid');
        }
        $menu = new MenuBot();
        $menu->slug = Str::slug($request->slug);
        $menu->content = $request->input('content');
        $menu->parent_id = $parent->id;
        $menu->save();
        $keys = [];
        foreach (array_filter(!is_array($request->keywords)?[]:$request->keywords) as $item) {
            $keys[] = [
                'keyword' => $item
            ];
        }
        $menu->keywords()->delete();
        if (count($keys) > 0):
            $menu->keywords()->createMany($keys);
        endif;
        return apiResponse(200, 'OK', 'OK');
    }

    public function delete(Request $request)
    {
        $menu = MenuBot::where('id',$request->get('id'))->first();
        if (!$menu) {
            return apiResponse(404,'Not found');
        }
        $menu->delete();
        return apiResponse(200, 'OK', 'OK');
    }
}
