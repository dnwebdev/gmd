<?php

namespace App\Http\Controllers\Company\Update;

use App\Models\Update;
use App\Models\UserAgent;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class UpdateCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $company = auth('web')->user()->company;
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.updates.index');
        }
        return view('dashboard.company.updates.index');
    }

    /**
     * function get Unread Notification Count
     *
     * @param mixed $request
     *
     * @return void
     */
    public function getUnreadNotificationCount(Request $request)
    {
        $type = 'info_promo';
        if ($request->has('type') && $request->type != '') {
            $type = $request->type;
        }
        if ($request->wantsJson() && $request->ajax()) {
            $company = UserAgent::find(auth('web')->id())->company;
            $notif = \DB::table('tbl_updates')
                ->select([
                    'tbl_updates.title',
                    'tbl_updates.title_indonesia',
                    'company_update.read_at',
                    'tbl_updates.type',
                    'tbl_updates.id',
                    'tbl_updates.created_at',
                ])
                ->join('company_update','company_update.id_update','tbl_updates.id')
                ->where('company_update.id_company',$company->id_company)
                ->where('tbl_updates.type', $type)
                ->orderByRaw('(CASE WHEN company_update.read_at IS NULL THEN 1 ELSE 0 END) DESC')
                ->orderBy('tbl_updates.created_at', 'desc')
                ->paginate(4)
                ->appends($request->all());
//            $notif = $company->updates()->where('type', $request->type)->paginate(16)->appends($request->all());
//            $data['unread_info_promo_count'] = $company->updates()->where('type', 'info_promo')->wherePivot('read_at', null)
//                ->count();
            $data['unread_info_promo_count'] = 0;
            $data['unread_new_features_count'] = $company->updates()->where('type', 'new_features')
                ->wherePivot('read_at', null)->count();
            $data['unread_patch_notes_count'] = $company->updates()->where('type', 'patch_notes')->wherePivot('read_at', null)
                ->count();
            $data['unread_upcoming_features_count'] = $company->updates()->where('type', 'upcoming_features')
                ->wherePivot('read_at', null)->count();
            $data['notifications'] = $notif;
            $data['unread_order_count'] = $company->order()->where('booking_type',
                'online')->where('is_read',0)->count();
            if (auth()->user()->company->is_klhk == 1){
                $data['view'] = view('klhk.dashboard.company.updates.pagination', compact('notif'))->render();
            }
            $data['view'] = view('dashboard.company.updates.pagination', compact('notif'))->render();
            return apiResponse(200, 'OK', $data);
        }
        abort(403);
    }

    /**
     * function read data update
     *
     * @param mixed $request
     *
     * @return void
     */
    public function readData(Request $request)
    {
        if ($request->wantsJson() && $request->ajax()) {
            $company = UserAgent::find(auth('web')->id())->company;
            $update = Update::find($request->id);
            if ($update && $company) {
                $company->updates()->updateExistingPivot($update->id, ['read_at' => Carbon::now()]);
                $data = $update->toArray();
                if (app()->getLocale() == 'id') {
                    $x = [
                        'title' => $data['title_indonesia'],
                        'content' => $data['content_indonesia'],
                        'date' => $data['created_at']
                    ];
                } else {
                    $x = [
                        'title' => $data['title'],
                        'content' => $data['content'],
                        'date' => $data['created_at']
                    ];
                }

                return apiResponse(200, 'OK', $x);
            }

        }
        abort(403);

    }
}
