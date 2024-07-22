<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends MainController
{
    //
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    }
    public function login(Request $req){
        $this->validate(
            $req,
            [
                'phone' => 'required',
                'password' => 'required|min:6|max:20'
            ],
            [
                'phone.required' => 'សូមបញ្ចូលឈ្មោះឬលេខទូរស័ព្ទឲ្យបានត្រឹមត្រូវ',
                'password.required' =>'សូមបញ្ចូលលេខសម្ងាត់ឲ្យបានត្រឹមត្រូវ',
                'password.min' => 'លេខសម្ងាត់យ៉ាងតិច៦ខ្ទង់',
                'password.max' => 'លេខសម្ងាត់យ៉ាងច្រើន២០ខ្ទង់',
            ]
        );
        $credentail=array(
            'phone' => $req->phone,
            'password' => $req->password,
        );
        
        try {
            // if(!Auth::attempt($credentail)){
            //     return response()->json(
            //         [
            //             'status' => false,
            //             'message' => 'ឈ្មោះឬលេខទូរស័ព្ទរបស់អ្នកមិនត្រឹមត្រូវ', 
            //         ],Response::HTTP_UNAUTHORIZED
            //     );
            // }
            // $user=User::where('phone',$req->phone)->first();
            // return response()->json(
            //     [
            //         'status' => true,
            //         'message' => 'ជោគជ័យ',
            //         'token' => $user->createToken('API')->plainTextToken,
            //     ],200
            // );
             // ===>> Set JWT Token Time To Live
            JWTAuth::factory()->setTTL(1200); //1200 នាទី
             // ===>> Credentails comparation by JWTAuth in DB using table user
            $token = JWTAuth::attempt($credentail);
            if(!$token){
                return response()->json([
                    'status'    => 'បរាជ័យ',
                    'message'   => 'ឈ្មោះអ្នកប្រើឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ។'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ],500);
        }
        $user=auth()->user();
        $dataUser = [
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'avatar'    => $user->avatar,
            'phone'     => $user->phone
        ];

        // ====> Check Role
        $role = '';
        if ($user->type_id == 2) { //
            $role = 'Staff';
        } else if($user->type_id==1){
            $role = 'Admin';
        }else{
            $role = 'Student';
        }
        // ===>> Success Response Back to Client
        return response()->json([
            'id' => $user->id,
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => JWTAuth::factory()->getTTL() / 60 . ' hours',
            'user'          => $dataUser,
            'role'          => $role
        ], Response::HTTP_OK);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // ===>> Make Application Logout
        auth()->logout();

        // ===>> Success Response Back to Client
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
    // public function logout()
    // {
    //     // Invalidate the token
    //     JWTAuth::invalidate(JWTAuth::getToken());

    //     // ===>> Success Response Back to Client
    //     return response()->json(['message' => 'Successfully logged out'], 200);
    // }
}
