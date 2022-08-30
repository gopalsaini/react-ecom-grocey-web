<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	 
    public function index(){

        $shippedResult=\App\Models\Sales_detail::select(DB::raw('MONTH(created_at)  as month'),DB::raw('SUM(amount)  as amount'))->groupBy('month')->orderBy('month','ASC')->where('order_status','10')->whereYear('created_at',date("Y"))->get()->toArray();
       
        $deliveredResult=\App\Models\Sales_detail::select(DB::raw('MONTH(created_at)  as month'),DB::raw('SUM(amount)  as amount'))->groupBy('month')->orderBy('month','ASC')->where('order_status','9')->whereYear('created_at',date("Y"))->get()->toArray();

        $shippedAmount=['0','0','0','0','0','0','0','0','0','0','0','0']; 

        $deliveredAmount=['0','0','0','0','0','0','0','0','0','0','0','0']; 

        if(!empty($shippedResult)){

            foreach($shippedResult as $shipvalue){

                $shippedAmount[$shipvalue['month']-1]=$shipvalue['amount'];
            }

        }

        if(!empty($deliveredResult)){

            foreach($deliveredResult as $delivervalue){

                $deliveredAmount[$delivervalue['month']-1]=$delivervalue['amount'];
            }

        }
       
        $shippedAmount=implode(',',$shippedAmount);
        $deliveredAmount=implode(',',$deliveredAmount);

        $totalPendingOrder=\App\Models\Sales::where('sales_details.order_status','1')->where('sales_details.payment_status','!=','1')->join('sales_details','sales_details.sale_id','=','sales.id')->groupBy('sales_details.order_id')->get()->count();

        $totalConfirmedOrder=\App\Models\Sales::where('sales_details.order_status','2')->where('sales_details.payment_status','!=','1')->join('sales_details','sales_details.sale_id','=','sales.id')->groupBy('sales_details.order_id')->get()->count();
        
        $totalShippedOrder=\App\Models\Sales::where('sales_details.order_status','10')->where('sales_details.payment_status','!=','1')->join('sales_details','sales_details.sale_id','=','sales.id')->groupBy('sales_details.order_id')->get()->count();
       
        $totalDeliveredOrder=\App\Models\Sales::where('sales_details.order_status','9')->where('sales_details.payment_status','!=','1')->join('sales_details','sales_details.sale_id','=','sales.id')->groupBy('sales_details.order_id')->get()->count();

        return view('admin.dashboard',compact('shippedAmount','deliveredAmount','totalPendingOrder','totalConfirmedOrder','totalShippedOrder','totalDeliveredOrder'));
    }

}
