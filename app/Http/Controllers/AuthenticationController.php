<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'status' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    public function getOtp() {
        $data = file_get_contents("php://input");
        $request = json_decode($data, TRUE);
        if($request) {
            if(empty($request['mobile_number'])) {
                $error_message = "Mobile number can not be empty";
                return $this->sendError($error_message, $error_message);
            }

            $otp = rand('100000', '999999');
            $mobile_number = $request['mobile_number'];
            $check_exists = User::where('mobile',$mobile_number)->first();
            if(!empty($check_exists) && empty($request['resend_otp'])) {
                $error_message = "Mobile number already registered. Please Sign in";
                return $this->sendError($error_message, $error_message);
            } elseif(!empty($check_exists) && !empty($request['resend_otp'])) {
                $check_exists->otp  = $otp;
                $check_exists->save();
                $registered_id = $check_exists->id;
            } elseif (empty($check_exists)) {
                $user = new User();
                $user->mobile = $mobile_number;
                $user->otp = $otp;
                $user->save();
                $registered_id = $user->id;
            }
            $this->sendMessage($mobile_number, $this->getOtpMessage($otp));
            $response_data = [
                "user_id" => $registered_id,
                "otp" => $otp
            ];
            return $this->sendResponse($response_data, 'OTP sent successfully');
        }
        $error_message = "Invalid Request";
        return $this->sendError($error_message, $error_message);
    }

    public function getOtpMessage($otp) {
        $message = 'OTP for Astro Buddy is - '.$otp;
        return $message;
    }

    public function sendMessage($mobile_number, $message) {
        return 1;
    }

    public function verifyOtp() {
        $data = file_get_contents("php://input");
        $request = json_decode($data, TRUE);
        if($request) {
            if(empty($request['user_id'])) {
                $error_message = "User id can not be empty";
                return $this->sendError($error_message, $error_message);
            }

            if(empty($request['otp'])) {
                $error_message = "OTP can not be empty";
                return $this->sendError($error_message, $error_message);
            }

            $get_user = User::where('id',$request['user_id'])->first();
            if(empty($get_user)) {
                $error_message = "User not exists. Please register";
                return $this->sendError($error_message, $error_message);
            } elseif(!empty($get_user) && $get_user->otp === $request['otp']) {
                return $this->sendResponse($get_user, 'OTP verified successfully');
            }
            $error_message = "OTP did not matched";
            return $this->sendError($error_message, $error_message);
        }
        $error_message = "Invalid Request";
        return $this->sendError($error_message, $error_message);
    }
}
