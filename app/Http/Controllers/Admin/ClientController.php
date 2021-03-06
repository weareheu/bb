<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Validator;
use Input;
use Auth;
use Redirect;
use Illuminate\Http\Request;
use App\Client;
use App\User as User;
use Hash;
use Mail;
use Storage;

class ClientController extends CommonController
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addclient(Request $request)
    {
        $rules = [
            'first_name'   => 'required|string|min:2|max:20',
            'last_name'    => 'required|string|min:2|max:8',
            'email'        => 'required|email',
            'phone_no'     => 'required|string',
            'password'     => 'required|string',
            'company_name' => 'required|string|min:2|max:30',
            'address'      => 'required|string|min:2|max:30',
            'state'        => 'required|string|min:2|max:30',
            'country'      => 'required|string|min:2|max:30',
            'zip_code'      => 'required|string|min:2|max:5',
            'gender'       => 'required|in:male,female',
            'date_of_joining' => 'required',
            'client_designation'    => 'required|string'
        ];
        $validator = Validator::make($request->all(),$rules);
        if (!$validator->fails()) 
        {
            $requestData = $request->all();
            $data['email'] = trim(strtolower($requestData['email']));
            $is_client_exists = User::where(['type' => 'client','email' => strtolower($requestData['email']),"deleted" => '0'])->first();  
            if(empty($is_client_exists))
            {
                $add_user_data = array(
                    'name'  => trim($requestData['first_name'])." ".trim($requestData['last_name']),
                    'email' => strtolower($data['email']),
                    'password' => Hash::make(trim($requestData['password'])),
                    'phone_no' => trim($requestData['phone_no']),
                    'address'  => trim($requestData['address']),
                    'state'    => trim($requestData['state']),
                    'country'  => trim($requestData['country']),
                    'zip_code' => trim($requestData['zip_code']),
                    'gender'   => trim($requestData['gender']),
                    'date_of_joining' => date('Y-m-d',strtotime(str_replace('/', '-', $requestData['date_of_joining']))),
                    'dob'      => '',
                    'status'   => '1',
                    'deleted'  => '0',
                    'profile_image' => '',
                    'type'    => 'client'
                );
                $to_email = $data['email'];
                $to_name = 'Client Registration';
                $add_user_data['plain_password'] = trim($requestData['password']); 
                Mail::send('admin.emails.ClientRegistration', $add_user_data, function($message) use ($to_name, $to_email) {
                    $message->to(strtolower($to_email), 'Tutorials Point')->subject($to_name);
                });
                $add_client_user = User::create($add_user_data);
                if($add_client_user)
                {
                    $client_add = array(
                        'status'   => '1',
                        'deleted'  => '0',
                        'company_name'  => trim($requestData['company_name']),
                        'client_designation'  => trim($requestData['client_designation']),
                        'user_id' => $add_client_user['id'],
                    );
                    Client::create($client_add);
                    $status   = 200;
                    $response = array(
                        'status'  => 'SUCCESS',
                        'message' => trans('messages.client_add_success'),
                        'ref'     => 'client_add_success',
                    );
                }
                else
                {
                    $status = 400;
                    $response = array(
                        'status'  => 'FAILED',
                        'message' => trans('messages.server_error'),
                        'ref'     => 'server_error'
                    );
                }
            }
            else
            {
                $status = 400;
                $response = array(
                    'status'  => 'FAILED',
                    'message' => trans('messages.error_client_email_exists'),
                    'ref'     => 'error_client_email_exists'
                );  
            }
        } else {
            $status = 400;
            $response = array(
                'status'  => 'FAILED',
                'message' => $validator->messages()->first(),
                'ref'     => 'missing_parameters',
            );
        }
        $data = array_merge(
            [
                "code" => $status,
                "message" =>$response['message']
            ],
            $response
        );
        array_walk_recursive($data, function(&$item){if(is_numeric($item) || is_float($item) || is_double($item)){$item=(string)$item;}});
        return \Response::json($data,200);
    }
    
    public function editclient(Request $request)
    {
        $rules = [
            'first_name'   => 'required|string|min:2|max:20',
            'last_name'    => 'required|string|min:2|max:8',
            'email'        => 'required|email',
            'phone_no'     => 'required|string',
            'company_name' => 'required|string|min:2|max:30',
            'address'      => 'required|string|min:2|max:30',
            'state'        => 'required|string|min:2|max:30',
            'country'      => 'required|string|min:2|max:30',
            'zip_code'      => 'required|string|min:2|max:5',
            'gender'       => 'required|in:male,female',
            'date_of_joining' => 'required',
            'client_designation'    => 'required|string',
            'client_id' => 'required',
            'id' => 'required',
            'profile_image'   => 'file|mimes:jpeg,png,jpg|max:5128',
        ];
        $validator = Validator::make($request->all(),$rules);
        if (!$validator->fails()) 
        {
            $requestData = $request->all();
            $is_client_exists = User::where(['type' => 'client','id' => (int) $requestData['id'],"deleted" => '0'])->first();
            if(!empty($is_client_exists))
            {  
                $data['email'] = trim(strtolower($requestData['email']));
                $is_email_exists = User::where('id', '!=' , $requestData['id'])->where(['type' => 'client','email' => strtolower($requestData['email']),"deleted" => '0'])->first(); 
                if(empty($is_email_exists))
                {
                    $edit_user_data = array(
                        'name'  => trim($requestData['first_name'])." ".trim($requestData['last_name']),
                        'email' => strtolower($data['email']),
                        'phone_no' => trim($requestData['phone_no']),
                        'address'  => trim($requestData['address']),
                        'state'    => trim($requestData['state']),
                        'country'  => trim($requestData['country']),
                        'zip_code' => trim($requestData['zip_code']),
                        'gender'   => trim($requestData['gender']),
                        'date_of_joining' => date('Y-m-d',strtotime(str_replace('/', '-', $requestData['date_of_joining'])))
                    );

                    /* Profile Image Save if exists Start */
                    if(!empty($requestData['profile_image']))
                    {
                        if (!empty($is_client_exists['profile_image'])) {
                            Storage::delete(config('app.folder') . '/' . config('app.profileimagesfolder').'/'.$is_client_exists['profile_image']);
                        }     
                        $filename = User::uploadImage(config('app.folder').'/'.config('app.profileimagesfolder'),$requestData['profile_image'],400);
                        if($filename) 
                            $edit_user_data['profile_image'] = $filename;
                    }
                    /* Profile Image Save if exists End */
                    //dd($edit_user_data);
                    if(!empty($requestData['password']))
                    {
                        $edit_user_data['password'] = Hash::make(trim($requestData['password']));
                    }
                    $edit_client = User::where('id', (int) $requestData['id'])->update($edit_user_data);
                    //dd($edit_client);
                    if($edit_client)
                    {
                        $client_data = Client::where(['user_id' => (int) $requestData['id'],"deleted" => '0'])->first();
                        if(empty($client_data))
                        {
                            $client_add = array(
                                'status'   => '1',
                                'deleted'  => '0',
                                'company_name'  => trim($requestData['company_name']),
                                'client_designation'  => trim($requestData['client_designation']),
                                'user_id' => $requestData['client_id'],
                            );
                            Client::create($client_add);
                        }
                        else
                        {
                            $client_edit = array(
                                'company_name'  => trim($requestData['company_name']),
                                'client_designation'  => trim($requestData['client_designation'])
                            );
                            $edit_client = Client::where('id', (int) $client_data['id'])->update($client_edit);
                        }
                        $status   = 200;
                        $response = array(
                            'status'  => 'SUCCESS',
                            'message' => trans('messages.client_edit_success'),
                            'ref'     => 'client_edit_success',
                        );
                    }
                    else
                    {
                        $status = 400;
                        $response = array(
                            'status'  => 'FAILED',
                            'message' => trans('messages.server_error'),
                            'ref'     => 'server_error'
                        );
                    }
                }
                else
                {
                    $status = 400;
                    $response = array(
                        'status'  => 'FAILED',
                        'message' => trans('messages.error_client_email_exists'),
                        'ref'     => 'error_client_email_exists'
                    );  
                }
            }
            else
            {
                $status = 400;
                $response = array(
                    'status'  => 'FAILED',
                    'message' => trans('messages.error_client_id_invalid'),
                    'ref'     => 'error_client_id_invalid'
                ); 
            }
        } else {
            $status = 400;
            $response = array(
                'status'  => 'FAILED',
                'message' => $validator->messages()->first(),
                'ref'     => 'missing_parameters',
            );
        }
        $data = array_merge(
            [
                "code" => $status,
                "message" =>$response['message']
            ],
            $response
        );
        array_walk_recursive($data, function(&$item){if(is_numeric($item) || is_float($item) || is_double($item)){$item=(string)$item;}});
        return \Response::json($data,200);
    }

    public function deleteclient(Request $request)
    {
        $rules = [
            'id' => 'required'
        ];
        $validator = Validator::make($request->all(),$rules);
        if (!$validator->fails()) 
        {
            $requestData = $request->all();
            $is_client_exists = User::where(['type' => 'client','id' => (int) $requestData['id'],"deleted" => '0'])->first();
            if(!empty($is_client_exists))
            {
                $delete_user_data = array(
                    "deleted" => "1"
                );
                $delete_client = User::where('id', (int) $requestData['id'])->update($delete_user_data);
                if($delete_client)
                {
                    $deleted = Client::where('user_id', (int) $requestData['id'])->update($delete_user_data);
                    $status   = 200;
                    $response = array(
                        'status'  => 'SUCCESS',
                        'message' => trans('messages.client_delete_success'),
                        'ref'     => 'client_delete_success',
                    );
                }
                else
                {
                    $status = 400;
                    $response = array(
                        'status'  => 'FAILED',
                        'message' => trans('messages.server_error'),
                        'ref'     => 'server_error'
                    );
                }
            }
            else
            {
                $status = 400;
                $response = array(
                    'status'  => 'FAILED',
                    'message' => trans('messages.error_client_id_invalid'),
                    'ref'     => 'error_client_id_invalid'
                ); 
            }
        } else {
            $status = 400;
            $response = array(
                'status'  => 'FAILED',
                'message' => $validator->messages()->first(),
                'ref'     => 'missing_parameters',
            );
        }
        $data = array_merge(
            [
                "code" => $status,
                "message" =>$response['message']
            ],
            $response
        );
        array_walk_recursive($data, function(&$item){if(is_numeric($item) || is_float($item) || is_double($item)){$item=(string)$item;}});
        return \Response::json($data,200);
    }

    public function statuschange(Request $request)
    {
        $rules = [
            'id' => 'required'
        ];
        $validator = Validator::make($request->all(),$rules);
        if (!$validator->fails()) 
        {
            $requestData = $request->all();
            $is_client_exists = User::where(['type' => 'client','id' => (int) $requestData['id'],"deleted" => '0'])->first();
            if(!empty($is_client_exists))
            {
                if($is_client_exists['status'])
                    $status_user_data['status'] = '0';
                else
                    $status_user_data['status'] = '1';
                $status_client = User::where('id', (int) $requestData['id'])->update($status_user_data);
                if($status_client)
                {
                    $status   = 200;
                    $response = array(
                        'status'  => 'SUCCESS',
                        'message' => trans('messages.client_statuschange_success'),
                        'ref'     => 'client_statuschange_success',
                    );
                }
                else
                {
                    $status = 400;
                    $response = array(
                        'status'  => 'FAILED',
                        'message' => trans('messages.server_error'),
                        'ref'     => 'server_error'
                    );
                }
            }
            else
            {
                $status = 400;
                $response = array(
                    'status'  => 'FAILED',
                    'message' => trans('messages.error_client_id_invalid'),
                    'ref'     => 'error_client_id_invalid'
                ); 
            }
        } else {
            $status = 400;
            $response = array(
                'status'  => 'FAILED',
                'message' => $validator->messages()->first(),
                'ref'     => 'missing_parameters',
            );
        }
        $data = array_merge(
            [
                "code" => $status,
                "message" =>$response['message']
            ],
            $response
        );
        array_walk_recursive($data, function(&$item){if(is_numeric($item) || is_float($item) || is_double($item)){$item=(string)$item;}});
        return \Response::json($data,200);
    }

    public function clients_list(Request $request)
    {
        $data['clients_list'] = User::select('users.*','clients.company_name','clients.client_designation')->where(['type' => 'client','users.deleted' => '0'])->Join('clients', 'clients.user_id' , '=', 'users.id')->get()->toArray();
        if(!empty($data['clients_list']))
        {
            foreach ($data['clients_list'] as $key => $clients_list) {
                if(!empty($clients_list['profile_image']))
                    $data['clients_list'][$key]['profile_image_url'] = User::image_url(config('app.profileimagesfolder'),$clients_list['profile_image']);
                else
                    $data['clients_list'][$key]['profile_image_url'] = '';
            }
        }
        return view('admin.clients.clients-list',$data);
    }

    public function clients(Request $request)
    {
        $data['clients_list'] = User::select('users.*','clients.company_name','clients.client_designation')->where(['type' => 'client','users.deleted' => '0'])->Join('clients', 'clients.user_id' , '=', 'users.id')->get()->toArray();
        if(!empty($data['clients_list']))
        {
            foreach ($data['clients_list'] as $key => $clients_list) {
                if(!empty($clients_list['profile_image']))
                    $data['clients_list'][$key]['profile_image_url'] = User::image_url(config('app.profileimagesfolder'),$clients_list['profile_image']);
                else
                    $data['clients_list'][$key]['profile_image_url'] = '';
            }
        }
        return view('admin.clients.index',$data);
    }

    public function getprofile($id)
    {
        $data['client'] = User::where(['id' => (int) $id,'type' => 'client',"deleted" => '0'])->first();
        if(!empty($data['client']))
        {
            $data['client']['client_data'] = Client::where(['user_id' => (int) $id,"deleted" => '0'])->first();
            $name = explode(' ',$data['client']['name']);
            $data['client']['first_name'] = isset($name[0]) ? $name[0] : "";
            $data['client']['last_name'] = isset($name[1]) ? $name[1] : "";
            //$data['client']['prefix'] = clientprefix;
            if(!empty($data['client']['profile_image']))
                $data['client']['profile_image_url'] = User::image_url(config('app.profileimagesfolder'),$data['client']['profile_image']);
            else
                $data['client']['profile_image_url'] = '';
                return view('admin.clients.profile',$data);
        }
        // else
        // {
        //     echo 'error';
        // }
    }
}
