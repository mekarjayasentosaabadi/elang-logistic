<?php

namespace App\Http\Controllers;

use auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(){
        $profile        = auth()->user();
        return view('pages.profile.index', compact('profile'));
    }

    //function update
    function update(Request $request){
        try {
            $request->validate([
                'name'          => 'required',
                'email'         => 'required|unique:users,email,'.auth()->user()->id.',id',
                'address'       => 'required',
                'phonenumber'   => 'required|unique:users,phone,'.auth()->user()->id.',id',
            ]);
            $dataUpdate = [
                'name'          => $request->name,
                'phone'         => $request->phonenumber,
                'address'       => $request->address,
                'email'         => $request->email
            ];
            User::where('id', auth()->user()->id)->update($dataUpdate);
            return ResponseFormatter::success([], 'Berhasil mengupdate data');
        } catch (Exception $error) {
            return ResponseFormatter::error([$error], 'Gagal Memperbaharui data');
        }
    }

    //function changepassword
    function changepassword(Request $request){
        try {
            $request->validate([
                'oldpassword'   => 'required',
                'newpassword'   => 'required',
                'confirmpassword'   => 'required|same:newpassword',
            ]);
            $cekhash = Hash::check($request->oldpassword, auth()->user()->password);
            if(!$cekhash){
                $pesan = ["Password lama tidak sesuai"];
                return ResponseFormatter::error([], $pesan,422);
            }
            $dataChangePassword = [
                'password'      => Hash::make($request->newpassword),
            ];
            User::where('id', auth()->user()->id)->update($dataChangePassword);
            return ResponseFormatter::success([], 'Password berhasil di perbaharui, silahkan login ulang untuk masuk aplikasi.!');
        } catch (Exception $error) {
            return ResponseFormatter::error([$error], 'Gagal Memperbaharui data');
        }
    }
}
