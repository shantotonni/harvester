<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest\ServiceRequestStoreRequest;
use App\Http\Requests\ServiceRequest\ServiceRequestUpdateRequest;
use App\Http\Resources\ServiceRequest\ServiceRequestJobCardCollection;
use App\Http\Resources\ServiceRequest\ServiceRequestJobCardResource;
use App\Models\ApprovedChassis;
use App\Models\Area;
use App\Models\CallType;
use App\Models\ChassisImage;
use App\Models\JobCard;
use App\Models\Products;
use App\Models\ServiceRequestDetails;
use App\Models\ServiceType;
use App\Models\Territory;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ServiceRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        $from_date = $request->from_date ? date('Y-m-d', strtotime($request->from_date)) : date('Y-m-01', strtotime(date('Y-m-01') . ' -1 month'));
        $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : date('Y-m-d');
        $searchId = $request->search ? $request->search : 0;
        $chassis_number = $request->chassis_number;
        $products = Products::all();
        $product_id = $request->product_id;

        if (Auth::user()->role_id == 1) {
            $job_cards = JobCard::query()->with(['area', 'territory', 'engineer', 'technitian', 'call_type', 'service_types', 'product', 'model']);

            if ($searchId) {
                $job_cards = $job_cards->where('id', $searchId);
            }
            if ($chassis_number) {
                $job_cards = $job_cards->where('chassis_number', $chassis_number);
            }
            if ($product_id) {
                $job_cards = $job_cards->where('product_id', $product_id);
            }

            $job_cards = $job_cards->whereDate('service_date', ">=", $from_date)
                ->whereDate('service_date', "<=", $to_date)
                ->Where('product_type', 'Harvester')
                ->paginate(50);
        } else {

            $user_id = Auth::user()->id;

            $job_cards = JobCard::query()->with(['area', 'territory', 'engineer', 'technitian', 'call_type', 'service_types', 'product', 'model']);

            if ($searchId) {
                $job_cards = $job_cards->where('id', $searchId);
            } elseif ($chassis_number) {
                $job_cards = $job_cards->where('chassis_number', $chassis_number);
            } elseif ($product_id) {
                $job_cards = $job_cards->where('product_id', $product_id);
            }

            $job_cards = $job_cards->where('engineer_id', $user_id)
                ->whereDate('service_date', ">=", $from_date)
                ->whereDate('service_date', "<=", $to_date)
                ->Where('product_type', 'Harvester')
                ->paginate(50);
        }
        return new ServiceRequestJobCardCollection($job_cards);
    }

    public function pendingJobCard(Request $request){

        $from_date = $request->from_date ? date('Y-m-d', strtotime($request->from_date)) : date('Y-m-01', strtotime(date('Y-m-01') . ' -1 month'));
        $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : date('Y-m-d');
        $searchId = $request->search ? $request->search : 0;
        $chassis_number = $request->chassis_number;
        $product_id = $request->product_id;

        if (Auth::user()->role_id == 1) {
            $job_cards = JobCard::query()->with(['area', 'territory', 'engineer', 'technitian', 'participant', 'call_type', 'service_types', 'products', 'model', 'image']);

            if ($searchId) {
                $job_cards = $job_cards->where('id', $searchId);
            }
            if ($chassis_number) {
                $job_cards = $job_cards->where('chassis_number', $chassis_number);
            }
            if ($product_id) {
                $job_cards = $job_cards->where('product_id', 'Harvester');
            }
            $job_cards = $job_cards->where('product_type', 'Harvester')
                //->whereDate('service_date', ">=", $from_date)
                //->whereDate('service_date', "<=", $to_date)
                ->where('job_status', 'started')
                ->orderBy('id', 'desc');
        } else {

            $user_id = Auth::user()->id;
            $job_cards = JobCard::query()->with(['area', 'territory', 'engineer', 'technitian', 'participant', 'call_type', 'service_types', 'products', 'model', 'image']);

            if ($searchId) {
                $job_cards = $job_cards->where('id', $searchId);
            } elseif ($chassis_number) {
                $job_cards = $job_cards->where('chassis_number', $chassis_number);
            } elseif ($product_id) {
                $job_cards = $job_cards->where('product_id', $product_id);
            }

            $job_cards = $job_cards
                ->where('product_type', 'Harvester')
                ->where('engineer_id', $user_id)
                //->whereDate('service_date', ">=", $from_date)
                //->whereDate('service_date', "<=", $to_date)
                ->where('job_status', 'started')->latest()->orderBy('id', 'desc');
        }

        $job_cards = $job_cards->paginate(50);

//        $job_cards = $this->sameDayChassisCheck($job_cards);


        return new ServiceRequestJobCardCollection($job_cards);
    }

    public function assignTechnician(Request $request){
        $job_card = JobCard::query()->where('id',$request->id)->first();
        $job_card->technitian_id = $request->technician_id;
        $job_card->save();
        return response()->json(['message' => 'Assigned Successfully', 200]);

    }

    public function approveJobCard(Request $request){

        $from_date = $request->from_date ? date('Y-m-d', strtotime($request->from_date)) : date('Y-m-01', strtotime(date('Y-m-01') . ' -1 month'));
        $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : date('Y-m-d');
        $searchId = $request->search ? $request->search : 0;
        $chassis_number = $request->chassis_number;
        $products = Products::all();
        $product_id = $request->product_id;
        $product_type = 'Harvester';

        if (Auth::user()->role_id == 1) {
            $job_cards = JobCard::query()->with(['area', 'territory', 'engineer', 'technitian', 'participant', 'call_type', 'service_types', 'products', 'model']);

            if ($searchId) {
                $job_cards = $job_cards->where('id', $searchId);
            }
            if ($chassis_number) {
                $job_cards = $job_cards->where('chassis_number', $chassis_number);
            }
            if ($product_id) {
                $job_cards = $job_cards->where('product_id', $product_id);
            }

            $job_cards = $job_cards->whereDate('service_date', ">=", $from_date)
                ->whereDate('service_date', "<=", $to_date)
                ->Where('product_type', $product_type)
                ->where('job_status', 'finished')
                ->orWhere('job_status', 'completed')
                ->latest()->orderBy('id', 'desc')
                ->paginate(50);
        } else {

            $user_id = Auth::user()->id;
            $job_cards = JobCard::query()->with(['area', 'territory', 'engineer', 'technitian', 'participant', 'call_type', 'service_types', 'products', 'model']);

            if ($searchId) {
                $job_cards = $job_cards->where('id', $searchId);
            } elseif ($chassis_number) {
                $job_cards = $job_cards->where('chassis_number', $chassis_number);
            } elseif ($product_id) {
                $job_cards = $job_cards->where('product_id', $product_id);
            }

            $job_cards = $job_cards->where('engineer_id', $user_id)
                ->whereDate('service_date', ">=", $from_date)
                ->whereDate('service_date', "<=", $to_date)
                ->Where('product_type', $product_type)
                ->where('job_status', 'finished')
                ->orWhere('job_status', 'completed')
                ->paginate(50);
        }

        return new ServiceRequestJobCardCollection($job_cards);
    }

    public function serviceRequestDetails($id){
        $job_cards = JobCard::with(['area', 'territory', 'engineer', 'technitian', 'participant', 'call_type', 'service_types', 'products', 'model'])->find($id);
        return new ServiceRequestJobCardResource($job_cards);
    }

    public function create(){

        $territories = Territory::all();
        $areas = Area::all();
        $users = User::all();
        $products = Products::all();
        $call_types = CallType::all();
        $service_types = ServiceType::all();
        return view("job_card.job_card_create")
            ->with("territories", $territories)
            ->with("areas", $areas)
            ->with("users", $users)
            ->with("products", $products)
            ->with("call_types", $call_types)
            ->with("service_types", $service_types);
    }

    public function store(ServiceRequestStoreRequest $request){

        $job_card = new JobCard;
        $job_card->territory_id = $request->territory_id;
        $job_card->area_id = $request->area_id;
        $job_card->engineer_id = $request->engineer_id;
        $job_card->technitian_id = $request->technitian_id;
        $job_card->participant_id = $request->participant_id;
        $job_card->product_id = $request->product_id;
        $job_card->call_type_id = $request->call_type_id;
        $job_card->service_type_id = $request->service_type_id;
        $job_card->customer_name = $request->customer_name;
        $job_card->customer_moblie = $request->customer_moblie;
        if ($request->buy_date) $job_card->buy_date = date("Y-m-d", strtotime($request->buy_date));
        $job_card->service_wanted_at = date("Y-m-d H:i:s", strtotime($request->service_wanted_at));
        $job_card->service_start_at = date("Y-m-d H:i:s", strtotime($request->service_start_at));
        $job_card->service_end_at = date("Y-m-d H:i:s", strtotime($request->service_end_at));
        $job_card->hour = $request->hour;
        $job_card->service_income = $request->service_income;
        $job_card->job_status = $request->job_status;
        $job_card->approver_id = $request->approver_id;
        $job_card->save();

        Session::flash("success", "Created Successfully !");
        return redirect("/service_request");
    }

    public function edit($id)
    {

        $territories = Territory::all();
        $areas = Area::all();
        $users = User::all();
        $products = Products::all();
        $call_types = CallType::all();
        $service_types = ServiceType::all();
        $job_card = JobCard::findOrFail($id);
        return view("job_card.job_card_edit", compact("job_card"))
            ->with("territories", $territories)
            ->with("areas", $areas)
            ->with("users", $users)
            ->with("products", $products)
            ->with("call_types", $call_types)
            ->with("service_types", $service_types);

    }

    public function update(ServiceRequestUpdateRequest $request, $id)
    {
//dd($request->all());
        $job_card = JobCard::findOrFail($id);
        $job_card->product_id = $request->product_id;
        $job_card->call_type_id = $request->call_type_id;
        $job_card->service_type_id = $request->service_type_id;
        $job_card->customer_name = $request->customer_name;
        $job_card->customer_moblie = $request->customer_moblie;
//        $job_card->buy_date= $request->buy_date ? date("Y-m-d",strtotime($request->buy_date)) : null;
//        $job_card->visited_date = $request->visited_date ? date("Y-m-d",strtotime($request->visited_date)) : null;
//        $job_card->installation_date = $request->installation_date ? date("Y-m-d",strtotime($request->installation_date)) : null;
//        $job_card->service_wanted_at = $request->service_wanted_at ? date("Y-m-d H:i:s",strtotime($request->service_wanted_at)) : null;
//        $job_card->service_start_at = $request->service_start_at ? date("Y-m-d H:i:s",strtotime($request->service_start_at)) : null;
//        $job_card->service_end_at = $request->service_end_at ? date("Y-m-d H:i:s",strtotime($request->service_end_at)) : null;
        $job_card->hour = $request->hour;
        $job_card->service_income = $request->service_income;
        //$job_card->is_approved = $request->is_approved;

        if ($request->is_approved == 1) {
            $job_card->approver_id = Auth::user()->id;
        } else {
            $job_card->approver_id = null;
        }
        $job_card->save();

        Session::flash("success", "Edited Successfully !");
        return redirect("/pending_job_card");
    }

    public function jobCardChassisUpdate(Request $request)
    {
        $job_card = JobCard::find($request->jobcardno);
        $job_card->chassis_number = $request->chassisno;
        $job_card->save();

        $chassis_image = ChassisImage::where('job_card_id', $request->jobcardno)->get()[0];
        $chassis_image->chassis_no = $request->chassisno;
        $chassis_image->is_approved = 1;
        $chassis_image->approved_by = Auth::user()->id;
        $chassis_image->save();

        $checkApprovedChassis = ApprovedChassis::where('chassis_no', $request->chassisno)->first();
        // dd($checkApprovedChassis);
        if (!isset($checkApprovedChassis)) {
            $approved_chassis = new ApprovedChassis();
            $approved_chassis->job_card_id = $request->jobcardno;
            $approved_chassis->chassis_no = $job_card->chassis_number;
            $approved_chassis->customer_name = $job_card->customer_name;
            $approved_chassis->customer_mobile = $job_card->customer_moblie;
            $approved_chassis->product_id = $job_card->product_id;
            $approved_chassis->product_model_id = $job_card->model_id;
            $approved_chassis->save();
        }

        return response()->json("success");
    }

    public function destroy($id)
    {
        $job_card = JobCard::findOrFail($id);
        $job_card_details = ServiceRequestDetails::where('job_card_id', $job_card->id)->get();
        foreach ($job_card_details as $job_card_detail) {
            $job_card_detail->delete();
        }

        $job_card->delete();
        Session::flash("success", "Deleted Successfully !");
        return redirect("/job_card");
    }

    public function approve(Request $request)
    {
        $job_card = JobCard::findOrFail($request->job_card_id);

        $datetime1 = new DateTime($job_card->service_wanted_at);
        $datetime2 = new DateTime($job_card->service_start_at);
        $interval = $datetime1->diff($datetime2);
        $interval = $interval->format('%H:%I:%S');
        $interval = round($interval);

        $job_card->is_approved = 1;
        $job_card->approve_remarks = $request->remark;
        $job_card->approver_id = Auth::user()->id;
        $job_card->is_six_hour = $interval;
        $job_card->save();


        Session::flash("success", "Approved Successfully !");
        return redirect()->back();
    }


}
