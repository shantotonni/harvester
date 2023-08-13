<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceRequest\ServiceRequestDetailCollection;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class ServiceRequestDetailController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $job_card_details = ServiceRequestDetails::orderBy('id','Desc')->paginate(20);
        return new ServiceRequestDetailCollection($job_card_details);

    }


    public function create(){

        $job_cards=ServiceRequest::all();
        $users=User::all();
        return view("job_card_detail.job_card_detail_create")
             ->with("job_cards" ,$job_cards)
             ->with("users" ,$users);;
    }


    public function store(Request $request){

        $job_card_detail= new ServiceRequestDetails;
        $job_card_detail->job_card_id=$request->job_card_id;
        $job_card_detail->user_id=$request->user_id;
        $job_card_detail->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/job_card_detail");
    }



    public function edit($id){

        $job_cards=ServiceRequest::all();
        $users=User::all();
        $job_card_detail = ServiceRequestDetails::findOrFail($id);
        return view("job_card_detail.job_card_detail_edit",compact("job_card_detail"))
             ->with("job_cards" ,$job_cards)
             ->with("users" ,$users);;

    }

    public function update(Request $request, $id) {

        $job_card_detail=ServiceRequestDetails::findOrFail($id);
        $job_card_detail->job_card_id=$request->job_card_id;
        $job_card_detail->user_id=$request->user_id;
        $job_card_detail->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/job_card_detail");
    }

    public function show($id){
        $job_card_detail = ServiceRequestDetails::find($id);
        return new ServiceRequestDetailCollection($job_card_detail);
    }


    public function destroy($id){
        $job_card_detail = ServiceRequestDetails::findOrFail($id);
        $job_card_detail ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/job_card_detail");
    }

}
