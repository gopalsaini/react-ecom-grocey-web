<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class TransactionController extends Controller
{
    
    public function index(){

		$result=\App\Models\Transaction::orderBy('id','desc')->get();
		return view('admin.transactions',compact('result'));

	}
}
