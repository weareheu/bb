@extends('layout.mainlayout')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
			
            <!-- Page Content -->
            <div class="content container-fluid">
            
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Clients</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/dashboard' : '#') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Clients</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">
                            <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_client"><i class="fa fa-plus"></i> Add Client</a>
                            <div class="view-icons">
                                <a href="clients" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                                <a href="clients-list" class="list-view btn btn-link"><i class="fa fa-bars"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <!-- Search Filter -->
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating">
                            <label class="focus-label">Client ID</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating">
                            <label class="focus-label">Client Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus select-focus">
                            <select class="select floating"> 
                                <option>Select Company</option>
                                <option>Global Technologies</option>
                                <option>Delta Infotech</option>
                            </select>
                            <label class="focus-label">Company</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <a href="#" class="btn btn-success btn-block"> Search </a>  
                    </div>     
                </div>
                <!-- Search Filter -->
                
                <div class="row staff-grid-row">
                    @if(!empty($clients_list))
                        @foreach($clients_list as $index => $client_list)
                            <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                                <div class="profile-widget">
                                   <div class="d-flex">
                                    <div class="profile-img-card">
                                        @if(!empty($client_list['profile_image_url']))
                                            <a href="{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/client-profile/'.$client_list['id'] : '#') }}" class="avatar"><img alt="{{isset($client_list['name']) ? ucwords($client_list['name']) : '-'}}" src="{{{$client_list['profile_image_url']}}}"></a>
                                        @else
                                           <!-- <a href="{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/client-profile/'.$client_list['id'] : '#') }}" class="avatar"><img alt="No Image" src="{{asset('img/profiles/avatar-21.jpg')}}"></a> -->
                                           <div class="symbol symbol-lg-75 symbol-primary">
                                             <span class="symbol-label font-size-h3 font-weight-boldest">
                                                {{ mb_substr($client_list['name'], 0, 1) }}
                                                    
                                             </span>
                                            </div>
                                         @endif
                                    </div>
                                    <div class="m-l-10"><h4 class="user-name m-t-10 mb-0"><a href="{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/client-profile/'.$client_list['id'] : '#') }}">{{isset($client_list['name']) ? $client_list['name'] : '-'}}</a></h4>
                                        <span class="text-muted"><h6>{{isset($client_list['client_designation']) ? $client_list['client_designation'] : '-'}}<h6></span>
                                    </div>
                                    
                                </div>
                                   

                                    <div class="dropdown profile-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_client{{$client_list['id']}}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_client{{$client_list['id']}}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                    </div>
                                    </div>
                                     <!-- Edit Client Modal -->
                                                <div id="edit_client{{$client_list['id']}}" class="modal custom-modal fade" role="dialog">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Client</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{ Form::open(array( 'id' => 'EditClient'.$client_list['id'])) }}
                                                                @csrf
                                                                <div class="col-md-12">
                                                                        <div class="profile-img-wrap edit-img">
                                                                            @if(!empty($client_list['profile_image_url']))
                                                                                <img id="imagePreview" class="inline-block" src="{{$client_list['profile_image_url']}}" alt="{{isset($client_list['name']) ? ucwords($client_list['name']) : '-'}}">
                                                                            @else
                                                                                <img id="imagePreview" class="inline-block" src="{{asset('img/profiles/avatar-21.jpg')}}" alt="No Image">
                                                                            @endif
                                                                            <div class="fileupload btn">
                                                                                <span class="btn-text">Edit</span>
                                                                                <input class="upload" type='file' name="profile_image" id="editimageUpload" accept=".png, .jpg, .jpeg" />
                                                                            </div>
                                                                        </div>
                                                                        </div>
                                                                    <input type="hidden" name="id" value="{{$client_list['id']}}">
                                                                    <div class="row" style="text-align: left !important">
                                                                        <?php $name = explode(' ', $client_list['name']);?>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                                                                <input class="form-control" value="{{isset($name[0]) ? ucwords($name[0]) : '-'}}" type="text" onkeyup="this.value = this.value.replace(/\s/g,'')" onkeypress="return /[A-Z a-z 0-9 _]+$/i.test(event.key)" name="first_name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Last Name</label>
                                                                                <input class="form-control" value="{{isset($name[1]) ? ucwords($name[1]) : '-'}}" type="text" onkeyup="this.value = this.value.replace(/\s/g,'')" onkeypress="return /[A-Z a-z 0-9 _]+$/i.test(event.key)" name="last_name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                                                                <input class="form-control floating" value="{{isset($client_list['email']) ? $client_list['email'] : '-'}}" type="email" name="email">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Password</label>
                                                                                <input class="form-control" name="password" type="password">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Address<span class="text-danger">*</span> </label>
                                                                                <input class="form-control" type="text" name="address" value="{{isset($client_list['address']) ? $client_list['address'] : '-'}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Gender</label>
                                                                                <select class="select form-control" name="gender">
                                                                                    <option {{$client_list['gender'] == "male" ? 'selected' : ''}} value="male">Male</option>
                                                                                    <option {{$client_list['gender'] == "female" ? 'selected' : ''}} value="female">Female</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Date Of Joining</label>
                                                                            <div class="form-group form-focus">
                                                                                <div class="cal-icon">
                                                                                    <input type="text" class="form-control floating datetimepicker" value="{{isset($client_list['date_of_joining']) ? date("d/m/y",strtotime($client_list['date_of_joining'])) : '-'}}" name="date_of_joining">
                                                                                </div>
                                                                                <label class="focus-label">Date Of Joining</label>
                                                                            </div>
                                                                        </div>  
                                                                         <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">State<span class="text-danger">*</span> </label>
                                                                                <input class="form-control" type="text" name="state" value="{{isset($client_list['state']) ? $client_list['state'] : '-'}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Country<span class="text-danger">*</span> </label>
                                                                                <input class="form-control" type="text" name="country" value="{{isset($client_list['country']) ? $client_list['country'] : '-'}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Zip Code<span class="text-danger">*</span> </label>
                                                                                <input class="form-control" type="text" name="zip_code" value="{{isset($client_list['zip_code']) ? $client_list['zip_code'] : '-'}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">  
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Client ID <span class="text-danger">*</span></label>
                                                                                <input class="form-control floating" value="{{config('app.clientprefix')}}-{{$client_list['id']}}" type="text" readonly="true" name="client_id">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Phone </label>
                                                                                <input class="form-control" value="{{isset($client_list['phone_no']) ? $client_list['phone_no'] : '-'}}" name="phone_no" type="text">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Company Name</label>
                                                                                <input class="form-control" type="text" value="{{isset($client_list['company_name']) ? $client_list['company_name'] : '-'}}" name="company_name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label">Client Designation<span class="text-danger">*</span></label>
                                                                                <input class="form-control" type="text" name="client_designation" value="{{isset($client_list['client_designation']) ? $client_list['client_designation'] : '-'}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="table-responsive m-t-15">
                                                                        <!-- <table class="table table-striped custom-table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Module Permission</th>
                                                                                    <th class="text-center">Read</th>
                                                                                    <th class="text-center">Write</th>
                                                                                    <th class="text-center">Create</th>
                                                                                    <th class="text-center">Delete</th>
                                                                                    <th class="text-center">Import</th>
                                                                                    <th class="text-center">Export</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>Projects</td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Tasks</td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Chat</td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Estimates</td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Invoices</td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Timing Sheets</td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input checked="" type="checkbox">
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table> -->
                                                                    </div>
                                                                    <div class="submit-section">
                                                                        <a onClick="EditClient('{{$client_list['id']}}')" class="btn btn-primary submit-btn">Save</a>
                                                                    </div>
                                                                 {{ Form::close() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /Edit Client Modal -->
                                    <!-- Delete Client Modal -->
                                                <div class="modal custom-modal fade" id="delete_client{{$client_list['id']}}" role="dialog">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="form-header">
                                                                    <h3>Delete Client</h3>
                                                                    <p>Are you sure want to delete?</p>
                                                                </div>
                                                                <div class="modal-btn delete-action">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <a onClick="DeleteClient('{{$client_list['id']}}')" href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /Delete Client Modal -->
                                    <div class="d-flex align-items-center justify-content-between m-t-10" >
                                    <span>
                                        Company Name:
                                    </span>    
                                    <span><h5 class="text-muted m-t-10 mb-0 text-ellipsis"><a href="{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/client-profile/'.$client_list['id'] : '#') }}"> {{isset($client_list['company_name']) ? $client_list['company_name'] : '-'}}</a></h5></span>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between m-t-5" >
                                    <span>
                                        Email:
                                    </span>    
                                    <span><h5 class="text-muted m-t-10 mb-0 text-ellipsis"><a href="{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/client-profile/'.$client_list['id'] : '#') }}"> {{isset($client_list['email']) ? $client_list['email'] : '-'}}</a></h5></span>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between m-t-5" >
                                    <span>
                                        Phone:
                                    </span>    
                                    <span><h5 class="text-muted m-t-10 mb-0 text-ellipsis"><a href="{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/client-profile/'.$client_list['id'] : '#') }}"> {{isset($client_list['phone_no']) ? $client_list['phone_no'] : '-'}}</a></h5></span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between m-t-5" >
                                    <span>
                                        Location:
                                    </span>    
                                    <span><h5 class="text-muted m-t-10 mb-0 text-ellipsis"><a href="{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/client-profile/'.$client_list['id'] : '#') }}"> {{isset($client_list['country']) ? $client_list['country'] : '-'}}</a></h5></span>
                                    </div>                                   
                                    
                                    <a href="chat" class="btn btn-light-success btn-sm m-t-10">Message</a>
                                    <a href="{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/client-profile/'.$client_list['id'] : '#') }}" class="btn btn-light-primary btn-sm m-t-10">View Profile</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                            No Record Found
                        </div>
                    @endif
                </div>
            </div>
            <!-- /Page Content -->
        
            <!-- Add Client Modal -->
            <div id="add_client" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{ Form::open(array( 'id' => 'AddClientForm')) }}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="first_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Last Name<span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="last_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                            <input class="form-control floating" type="email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Password<span class="text-danger">*</span></label>
                                            <input class="form-control" type="password" name="password">
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Address<span class="text-danger">*</span> </label>
                                            <input class="form-control" type="text" name="address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select class="select form-control" name="gender">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Date Of Joining</label>
                                        <div class="form-group form-focus">
                                            <div class="cal-icon">
                                                <input type="text" class="form-control floating datetimepicker" value="" name="date_of_joining">
                                            </div>
                                            <label class="focus-label">Date Of Joining</label>
                                        </div>
                                    </div>  
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">State<span class="text-danger">*</span> </label>
                                            <input class="form-control" type="text" name="state">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Country<span class="text-danger">*</span> </label>
                                            <input class="form-control" type="text" name="country">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Zip Code<span class="text-danger">*</span> </label>
                                            <input class="form-control" type="text" name="zip_code">
                                        </div>
                                    </div>                              
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Phone<span class="text-danger">*</span> </label>
                                            <input class="form-control" type="text" name="phone_no">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Company Name<span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="company_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Client Designation<span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="client_designation">
                                        </div>
                                    </div>
                                                                      
                                </div>
                                
                                <div class="submit-section">
                                    <a onClick ="addClient()" class="btn btn-primary submit-btn">Submit</a>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Add Client Modal -->
            
            <!-- Edit Client Modal -->
            <!-- <div id="edit_client" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                            <input class="form-control" value="Barry" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Last Name</label>
                                            <input class="form-control" value="Cuda" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Username <span class="text-danger">*</span></label>
                                            <input class="form-control" value="barrycuda" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                            <input class="form-control floating" value="barrycuda@example.com" type="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Password</label>
                                            <input class="form-control" value="barrycuda" type="password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Confirm Password</label>
                                            <input class="form-control" value="barrycuda" type="password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">  
                                        <div class="form-group">
                                            <label class="col-form-label">Client ID <span class="text-danger">*</span></label>
                                            <input class="form-control floating" value="CLT-0001" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Phone </label>
                                            <input class="form-control" value="9876543210" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Company Name</label>
                                            <input class="form-control" type="text" value="Global Technologies">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="table-responsive m-t-15">
                                    <table class="table table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th>Module Permission</th>
                                                <th class="text-center">Read</th>
                                                <th class="text-center">Write</th>
                                                <th class="text-center">Create</th>
                                                <th class="text-center">Delete</th>
                                                <th class="text-center">Import</th>
                                                <th class="text-center">Export</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Projects</td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tasks</td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Chat</td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Estimates</td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Invoices</td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Timing Sheets</td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                                <td class="text-center">
                                                    <input checked="" type="checkbox">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- /Edit Client Modal -->
            
            <!-- Delete Client Modal -->
            <!-- <div class="modal custom-modal fade" id="delete_client" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Client</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- /Delete Client Modal -->
            

        </div>
        <!-- /Page Wrapper -->

        <script type="text/javascript">

            function addClient() {
                var url = "{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/addclient' : '#') }}";  
                var form = $('#AddClientForm').get(0);
                var formData = new FormData(form);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response)
                    {
                        if(response.status == "SUCCESS")
                        {
                            toastr['success'](response.message);
                            window.location = "";
                        }
                        else
                        {
                            toastr['error'](response.message);
                        }    
                    }
                }); 
            }

            function EditClient(id)
            {
                var url = "{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/editclient' : '#') }}";
                var form = $('#EditClient'+id).get(0);
                var formData = new FormData(form);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response)
                    {
                        if(response.status == "SUCCESS")
                        {
                            toastr['success'](response.message);
                            window.location = "";
                        }
                        else
                        {
                            toastr['error'](response.message);
                        }    
                    }
                });
            }
            
            function DeleteClient(id)
            {
                var url = "{{ URL::to(isset(Auth::user()->type) ? Auth::user()->type.'/deleteclient' : '#') }}";
                var form = $('#EditClient'+id).get(0);
                var formData = new FormData(form);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response)
                    {
                        if(response.status == "SUCCESS")
                        {
                            toastr['success'](response.message);
                            window.location = "";
                        }
                        else
                        {
                            toastr['error'](response.message);
                        }    
                    }
                });
            }
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onloadend = function(e) {
                        $('#imagePreview').attr('src', e.target.result);
                    }
                    if (input) { 
                        reader.readAsDataURL(input.files[0]);
                        return true;
                    } else {
                        return false;
                    }
                }
                else
                    return false;
            }
            $("#editimageUpload").change(function() {
                var upload = readURL(this);
                if(upload)
                {
                }
            });
        </script>
@endsection

