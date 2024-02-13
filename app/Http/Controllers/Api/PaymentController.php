<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\merchantTransactionId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends BaseController
{
    public function prepareCheckout(Request $request)
    {
        return response()->json([
            'status_code' => 409,
            'message' => "هذه الخدمة غير متاحة",
        ], 409);

        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'email' => 'required',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required|min:2|max:2',  //country code
            'postcode' => 'required',
            'givenName' => 'required',
            'surname' => 'required',
            'visa_or_mada' => 'required|in:1,2',  //1 will be visa 2 will be mada
        ]);

        if ($validator->fails()) {
            return $this->apiErrorResponse($validator->messages()->first(), 422);
        }


        $email=$request->input('email');
        $street=$request->input('street');
        $city=$request->input('city');
        $state=$request->input('state');
        $country=$request->input('country');
        $postcode=$request->input('postcode');
        $givenName=$request->input('givenName');
        $surname=$request->input('surname');
        $paymentMethodChoice=$request->input('visa_or_mada');



        $allids=merchantTransactionId::pluck('marchant_transaction')->toArray();

        do{
            $token_id = str_random(10);
        }
        while(in_array($token_id, $allids));


        $craete=merchantTransactionId::create([
            'marchant_transaction' => $token_id,
            'user_id'              => auth()->user()->id
        ]);


        $entity_id='';
        if($paymentMethodChoice==1){                                         //visa choice
            $entity_id = "8ac7a4c7731539d00173254d80d51ea7" ;
        }elseif ($paymentMethodChoice==2) {                                  //Mada choice
            $entity_id = "8ac7a4c7731539d00173254e37b91eab" ;
        }else{
            return $this->apiErrorResponse('you should be choose payment method type visa and mastercart or Mada  ');
        }

        $paid_amount=$request->input('amount');

        $url = "https://test.oppwa.com/v1/checkouts";
            $data = "entityId={$entity_id}" .
            "&amount={$paid_amount}" .
            "&currency=SAR" .
            "&testMode=EXTERNAL".
            "&merchantTransactionId={$token_id}" .
            "&customer.email={$email}" .
            "&billing.street1={$street}" .
            "&billing.city={$city}" .
            "&billing.state={$state}" .
            "&billing.country={$country}" .
            "&billing.postcode={$postcode}" .
            "&customer.givenName={$givenName}".
            "&customer.surname={$surname}" .
            "&paymentType=DB" ;


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Yzc3MzE1MzlkMDAxNzMyNTRjZmE5MjFlYTJ8UGEzdGRjdEVnZA=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $sss=json_decode($responseData,true);

        return $sss;

    }




    public function get_payment_status(Request $request){
        $validator = Validator::make($request->all(), [
            'checkout_id' => 'required',
            'visa_or_mada' => 'required|in:1,2',  //1 will be visa 2 will be mada

        ]);

        if ($validator->fails()) {
            return $this->apiErrorResponse($validator->messages()->first(), 422);
        }

        $paymentMethodChoice=$request->input('visa_or_mada');
            $entity_id='';
        if($paymentMethodChoice==1){                                         //visa choice
            $entity_id = "8ac7a4c7731539d00173254d80d51ea7" ;
        }elseif ($paymentMethodChoice==2) {                                  //Mada choice
            $entity_id = "8ac7a4c7731539d00173254e37b91eab" ;
        }else{
            return $this->apiErrorResponse('you should be choose payment method type visa and mastercart or Mada  ');
        }


        $dataa=\request()->get('checkout_id');
        $url = "https://test.oppwa.com/v1/checkouts/$dataa/payment";
        $url .= "?entityId={$entity_id}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Yzc3MzE1MzlkMDAxNzMyNTRjZmE5MjFlYTJ8UGEzdGRjdEVnZA=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;

    }




}
