
@extends('layouts/master')

@section('title',__('Change Password'))

@section('Changepassword',__('active'))

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->

   <!-- Main content -->
   <section class="content">
      <div class="row">
         <!-- Form controls -->
         <div class="col-sm-12">
            <div class="panel panel-bd ">
               <div class="panel-heading">
					<i class="fa fa-th"></i> &nbsp; Change password
				</div>
               <div class="panel-body">
                  <form id="form" action="" method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group col-md-12">
                        <label for="inputName">Enter Old Password <span class="text-danger">*</span></label>
                        <input required type="text"  style="width:50%" id="inputName" class="form-control" name="old_pass">
                     </div>
                     <div class="form-group col-md-12">
                        <label for="inputName">Enter New Password <span class="text-danger">*</span></label>
                        <input required type="text"  style="width:50%" id="inputName" class="form-control" name="password">
                     </div>
                     <div class="form-group col-md-12">
                        <label for="inputName">Enter Confirm  Password <span class="text-danger">*</span></label>
                        <input required type="text"  style="width:50%" id="inputName" class="form-control" name="confirm_password">
                     </div>
                     <div class="form-group col-md-12">
                        <input type="submit" class="btn btn-primary" value="Submit">
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>
@endsection

