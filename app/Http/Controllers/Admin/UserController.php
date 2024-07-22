<?php

namespace App\Http\Controllers\Admin;
use App\Models\User\Type;
use App\Models\User\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController
{
    //
    public function getUserType(){
        $data=Type::get();
        return response()->json(
            [
             'message' => 'ជោគជ័យ',
             'data' =>  $data
            ],200
         );
    }




    public function getData(){

        // ===>> Get Data from DB
        // $data = UserModel::select('id', 'name', 'phone', 'email', 'type_id', 'avatar')
        // ->with([
        //     'type' // M:1
        // ]);
        $data=UserModel::get();

        // ===>>> Filter
        // By Key for Name or Phone Number
        // if ($req->key && $req->key != '') {
        //     $data = $data->where('name', 'LIKE', '%' . $req->key . '%')->Orwhere('phone', 'LIKE', '%' . $req->key . '%');
        // }

        // Order Data from Latest ID
        // $data = $data->orderBy('id', 'desc');

        // Pagination limited by 10
        // ->paginate($req->limit ? $req->limit : 10,);

        // Success Response Back to Client
        return response()->json(
            [
                'message' => 'ជោគជ័យ',
                'data' => $data
            ],200
        );
    }

    public function view($id = 0){

        // ===>> Get Data From Database
        $data = UserModel::select('id', 'name', 'phone', 'email', 'type_id', 'avatar')->with(['type'])->find($id);

        // Check if Data is valid.
        if ($data) { // Yes

            // ===> Success Response Back to Client
            return response()->json([
                
                'message' => 'ជោគជ័យ',
                'data' => $data,
            ],200);

        } else { // No

            // ===> Failed Response Back to Client
            return response()->json([
                'status'  => 'បរាជ័យ',
                'message' => 'រកទិន្នន័យមិនឃើញក្នុងប្រព័ន្ធ'
            ], 422);

        }
    }

    public function createUser(Request $req)
    {
        // Validate the request
        $val = $req->validate(
            [
                'type_id' => 'required|min:1|max:20',
                'name' => 'required|min:4|max:20',
                'email' => 'required|email|unique:user,email',
                'phone' => 'required|unique:user,phone',
                'password' => 'required|min:6|max:20',
            ],
            [
                'type_id.required' => 'សូមបញ្ចូលប្រភេទអ្នកប្រើប្រាស់',
                'type_id.min' => 'បញ្ចូលប្រភេទអ្នកប្រើប្រាស់យ៉ាងតិច១ខ្ទង់',
                'name.required' => 'សូមបញ្ចូលឈ្មោះអ្នកប្រើប្រាស់',
                'name.min' => 'ឈ្មោះអ្នកប្រើប្រាស់យ៉ាងតិច៤ខ្ទង់',
                'name.max' => 'ឈ្មោះអ្នកប្រើប្រាស់ច្រើនបំផុត២០ខ្ទង់',
                'email.required' => 'សូមបញ្ចូលអ៊ីម៉ែលអោយបានត្រឹមត្រូវ',
                'email.email' => 'សូមបញ្ចូលអ៊ីម៉ែលដែលត្រឹមត្រូវ',
                'email.unique' => 'អ៊ីម៉ែលនេះបានប្រើប្រាស់រួចហើយ',
                'phone.required' => 'សូមបញ្ចូលលេខទូរស័ព្ទអោយបានត្រឹមត្រូវ',
                'phone.unique' => 'លេខទូរស័ព្ទនេះបានប្រើប្រាស់រួចហើយ',
                'password.required' => 'សូមបញ្ចូលលេខសម្ងាត់',
                'password.min' => 'លេខសម្ងាត់យ៉ាងតិច៦ខ្ទង់',
                'password.max' => 'លេខសម្ងាត់យ៉ាងច្រើន២០ខ្ទង់',
            ]
        );

        try {
            $user = new UserModel;
            $user->type_id  = $val['type_id'];
            $user->name     = $val['name'];
            $user->email    = $val['email'];
            $user->phone    = $val['phone'];
            $user->password = Hash::make($val['password']); // Hash the password
            $user->avatar   = '';
            $user->created_at = now();
            $user->save();

            if ($req->hasFile('avatar')) {
                $destination_path = 'public/images/icon';
                $avatar = $req->file('avatar');
                $avatar_name = $avatar->getClientOriginalName();
                $path = $avatar->storeAs($destination_path, $avatar_name);
                $user->avatar = $avatar_name;
                $user->save();
            }

            // Return a JSON response with a success message and the created user data
            return response()->json(
                [
                    'message' => 'ជោគជ័យ',
                    'user' => UserModel::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at')
                        ->with(['type'])
                        ->find($user->id),
                ], 201
            );
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }


    public function update(Request $req, $id = 0){

        // ==>> Check validation
        $req->validate(
            [
                'name'     => 'required',
                'phone'    => 'required',
            ],
            [
                'name.required'     => 'សូមវាយបញ្ចូលឈ្មោះរបស់អ្នក',
                'phone.required'    => 'សូមវាយបញ្ចូលលេខទូរស័ព្ទរបស់អ្នក',
            ]
        );

        // Unique Phone Number Validation
        $check  = UserModel::where('id','!=',$id)->where('phone',$req->phone)->first();
        if($check){ // Yes

            // ===> Failed Response Back to Client
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'លេខទូរស័ព្ទនេះត្រូវបានប្រើប្រាស់រួចហើយនៅក្នុងប្រព័ន្ធ',
            ], 422);

        }

         // Unique Email Validation
        $check  = UserModel::where('id','!=',$id)->where('email',$req->email)->first();
        if($check){ // Yes

            // ===> Failed Response Back to Client
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ',
            ], 400);

        }

        //==============================>> Start Updating data
        // Get Data from DB
        $user = UserModel::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at')->with(['type'])->find($id);
        if ($user) { // Yes

            // Mapping between database table field and requested data from client
            $user->name      =   $req->name;
            $user->type_id   =   $req->type_id;
            $user->phone     =   $req->phone;
            $user->email     =   $req->email;
            $user->avatar =   '';

            // Call to File Service
            // if ($req->image) {

            //     // Call File Service
            //     $image     = FileUpload::uploadFile($req->image, 'users', $req->fileName);

            //     // Only valid url can be used.
            //     if ($image['url']) {

            //         // Mapping between database table field and uri from File Service
            //         $user->avatar = $image['url'];

            //     }
            // }

            // ===>> Save to DB
            $user->save();
            if ($req->hasFile('avatar')) {
                $destination_path = 'public/images/icon';
                $avatar = $req->file('avatar');
                $avatar_name = $avatar->getClientOriginalName();
                $path = $avatar->storeAs($destination_path, $avatar_name);
                $user->avatar = $avatar_name;
                $user->save();
            }

            // ===>> Success Response Back to Client
            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានកែប្រែ',
                'user'      => $user,
            ],200);

        } else { // No

            // ===>> Failed Response Back to Client
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យដែលផ្តល់ឲ្យមិនត្រូវទេ',
            ], 422);

        }
    }

    public function delete($id = 0){

        // ===>> Get Data from DB
        $data = UserModel::find($id);

        //====>> Check if Data is Valid
        if ($data) { // Yes

            // Delete Data from DB
            $data->delete();

            // ===>> Success Response Back to Client
            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានលុយចេញពីប្រព័ន្ធ',
            ], 200);

        } else { // No

            // ===>> Failed Response Back to Client
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យដែលផ្តល់ឲ្យមិនត្រូវ',
            ],400);

        }
    }

    public function changePassword(Request $req, $id = 0){

        // ===>> Check validation
        $req->validate(
            [
                'password' => 'required|min:6|max:20',
                'confirm_password'  => 'required|same:password',
            ], 
            [
                'password.required' => 'សូមបញ្ចូលលេខសម្ងាត់',
                'password.min'      => 'សូមបញ្ចូលលេខសម្ងាត់ធំជាងឬស្មើ៦',
                'password.max'      => 'សូមបញ្ចូលលេខសម្ងាត់តូចឬស្មើ២០',
                'confirm_password.required' => 'សូមបញ្ចូលបញ្ជាក់ពាក្យសម្ងាត់',
                'confirm_password.same'     => 'សូមបញ្ចូលបញ្ជាក់ពាក្យសម្ងាត់ឲ្យដូចលេខសម្ងាត់',

            ]
        );

        // ===>> Get User from DB
        $user = UserModel::find($id);

        // ===>> Check if User is Valid
        if ($user) { // Yes

            // Mapping between database table field and requested data from client
            $user->password                 = Hash::make($req->password); //Make sure no one can understand it even DB Admin.
            $user->updated_at = Carbon::now()->format('Y-m-d H:i:s');

            // Save to DB
            $user->save();

            // ===>> Success Response Back to Client
            return response()->json([
                'message' => 'លេខសម្ងាត់របស់ត្រូវបានកែប្រែ',
                'user' => $user],
            200);

        } else { // No

            // ===>> Failed Response Back to Client
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'មិនមានទិន្នន័យក្នុងប្រព័ន្ធ',
            ], 404);

        }
    }

}
