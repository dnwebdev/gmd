<?php

namespace App\Http\Controllers\Backoffice\Directory\Career;

use App\Models\JobApplicant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplicantCtrl extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.page.directory.career.applicant');
    }

    public function loadData()
    {
        $model = JobApplicant::query()->has('job_vacancy')->with('job_vacancy')->select('job_applicants.*');
        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ( $model ) {
                $html = '<a href="' . route('admin:directory.job-applicant.download', [ 'id' => $model->id ]) . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-download"></i></a>';
                if ($model->read_at == null) {
                    $html .= ' <button data-name="' . $model->full_name . '" data-id="' . $model->id . '" class="btn-read btn btn-outline-primary  btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                    $html .= '<i class="fa fa-eye"></i>';
                    $html .= '</button>';
                }
                return $html;
            })
            ->make(true);
    }

    public function download( $id )
    {
        if (!$jobApplicant = JobApplicant::find($id)) {
            msg('Job not found', 2);
            return redirect()->route('admin:directory.job-applicant.index');
        }
        return \Storage::disk('public')->download($jobApplicant->portfolio);
    }

    public function markAsRead( Request $request )
    {
        if (!$jobApplicant = JobApplicant::find($request->id)) {
            msg('Job not found', 2);
            return redirect()->route('admin:directory.job-applicant.index');
        }
        $jobApplicant->update([ 'read_at' => Carbon::now()->toDateTimeString() ]);
        msg('Job has been marked as read', 1);
        return redirect()->route('admin:directory.job-applicant.index');
    }
}
