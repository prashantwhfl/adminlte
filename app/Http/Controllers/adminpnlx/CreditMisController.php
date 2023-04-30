<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\CreditMis;
use Illuminate\Http\Request;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Mail,mongoDate,Redirect,Response,Session,URL,View,Validator;
use Illuminate\Database\Eloquent\Model;
/**
* AdminLogin Controller
*
* Add your methods in the class below
*
* This file will render views\admin\login
*/
class CreditMisController extends BaseController
{
    public function index()
    {   
        return View::make('admin.creditmis.index');
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            $data = array_map('str_getcsv', file($path));

            if (count($data) > 0) {
                foreach ($data as $row) {
                    $deal_number = $row[0];
                    $credit = CreditMis::where('deal_number', $deal_number)->first();

                    if ($credit) {
                        $credit->loan_number = $row[1];
                        $credit->disbursal_amount = $row[2];
                        $credit->disbursal_status = $row[3];
                        $credit->disbursal_date = $row[4];
                        $credit->save();
                    }
                }
            }
        }
        // Redirect the user back to the form with a success message
        Session::flash('success', trans("Onboarding has been added successfully"));
        return Redirect::back()->with('success', 'CSV file uploaded and table updated successfully');
    }
}