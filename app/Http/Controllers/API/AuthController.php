<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {
//validation
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3'
        ],[
            'name.required' => 'ป้อนข้อมูลชื่อด้วย',
            'email.required' => 'ป้อนข้อมูลอีเมล์ด้วย',
            'email.email' => 'รูปแบบอีเมล์ไม่ถูกต้อง',
            'email.unique' => 'มีผู้ใช้งานอีเมล์นี้ในระบบแล้ว',
            'password.required' => 'ป้อนข้อมูลรหัสผ่านด้วย',
            'password.min' => 'ป้อนข้อมูลรหัสผ่านอย่างน้อย 3 ตัวอักษร',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => [
                    'message' => $validator->errors()
                ]
            ], 422);
        }
        //validation

        //เพิ่ม user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'สมัครสมาชิกสำเร็จ'
        ], 201);
    }


    public function login (Request $request) {

 //validation
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:3',
            'device_name' => 'required'
        ],[
            'email.required' => 'ป้อนข้อมูลอีเมล์ด้วย',
            'email.email' => 'รูปแบบอีเมล์ไม่ถูกต้อง',
            'password.required' => 'ป้อนข้อมูลรหัสผ่านด้วย',
            'password.min' => 'ป้อนข้อมูลรหัสผ่านอย่างน้อย 3 ตัวอักษร',
            'device_name.required' => 'ป้อนข้อมูลด้วยครับ',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => [
                    'message' => $validator->errors()
                ]
            ], 422);
        }
        //validation
        //เช็ค email & password ว่าถูกำต้องหรือไม่
    $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                     'email' => ['อีเมล์หรือรหัสผ่านไม่ถูกต้อง'],
    ]);
}

      $token =  $user->createToken($request->device_name)->plainTextToken;

      $personal_token = PersonalAccessToken::findToken($token);

      return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $personal_token->created_at->addMinutes(config('sanctum.expiration'))
      ], 200);

    }

   //logout [post]
    public function logout(Request $request) {
        //หาค่าของคอลัมน์ id ของ token ปัจจุบันที่กำลังล็อกอินอยู่
        $id = $request->user()->currentAccessToken()->id;

        //ลบ record token user ในตารางฐานข้อมูล
        $request->user()->tokens()->where('id', $id)->delete();

        return response()->json([
            'message' => 'ออกจากระบบเรียบร้อยแล้ว'
        ], 200);
    }


    public function me (Request $request) {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'fullname' =>$user->officer->fullname,
                'picture_url' =>$user->officer->picture_url,
                'department' =>$user->officer->department->name

            ]

        ], 200);

    }
}
