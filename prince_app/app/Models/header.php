<?php

namespace App\Models;
use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Eloquent\Model;

class header extends Model
{
    public static function fetchData($entry_id){
		
		
		
	  $results = DB::table('permissions')->where('parent_id', $entry_id)->get();

		$treeEntryList = [];
		
		foreach ($results as $result) {

			$data = [
				'id' => $result->id,
				'name'=>ucfirst($result->name),
				'parent_id' => $result->parent_id,
				'url'=>$result->url,
				'icon'=>$result->icon,
				'children' => header::fetchData($result->id)
			];

			$treeEntryList[] = $data;
		}
		return $treeEntryList;

    }
	
	public static function AdminData(){
		$id = Auth::user()->id;
		$role_id = DB::table('model_has_roles')->where('model_id',$id)->first();
		return $resultsuser = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$role_id->role_id)
            ->get();
			
		 
    }

}
