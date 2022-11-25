<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;


class ChangePasswordController
{
    public function changePassword(Request $request) {
        $request->validate([
            'oldPassword' => ['required','string','min:6'],
            'newPassword' => ['required','string','min:6'],
            'confirmPassword' => ['required','string','min:6']
        ]);

        $oldPassword = $request->get('oldPassword');
        $newPassword = bcrypt($request->get('newPassword'));
        $confirmPassword = bcrypt($request->get('confirmPassword'));

        $currentUserPassword = \Auth::user()->password;
        $currentUserId = \Auth::user()->id;

        if (Hash::check($oldPassword, $currentUserPassword)) {
            if($newPassword !== $confirmPassword) {
                $updatePassword = User::find($currentUserId);

                $updatePassword->password = $confirmPassword;

                if($updatePassword->save()) {
                    $message = "Password change successful";
                } else {
                    $message = "Error occurred";
                }
            } else {
                $message = "Password do not match";
            }
        } else {
            $message = "Old password incorrect";
        }

        return response([
            "message" => $message
        ],Response::HTTP_OK);

    }

}
