<?php

namespace App\Http\Livewire\Payment;

use App\Models\Order;
use App\Http\Livewire\BaseLivewireComponent;
use App\Traits\FlutterwaveTrait;
use App\Traits\PaystackTrait;
use App\Traits\RazorPayTrait;
use App\Traits\StripeTrait;
use Exception;

class OrderPaymentCallbackLivewire extends BaseLivewireComponent
{

    use StripeTrait, RazorPayTrait, PaystackTrait, FlutterwaveTrait;
    public $code;
    public $status;
    public $transaction_id;
    public $error;
    public $errorMessage;
    protected $queryString = ['code','status','transaction_id'];


    public function render()
    {
        //

        $this->selectedModel = Order::where('code', $this->code)->first();

        //
        if (empty($this->selectedModel)) {
            return view('livewire.payment.invalid')->layout('layouts.auth');
        } else {

            try{
                if( $this->selectedModel->payment_method->slug == "stripe" ){
                    $this->verifyStripeTransaction( $this->selectedModel );
                }else if( $this->selectedModel->payment_method->slug == "razorpay" ){
                    $this->verifyRazorpayTransaction( $this->selectedModel );
                }else if( $this->selectedModel->payment_method->slug == "paystack" ){
                    $this->verifyPaystackTransaction( $this->selectedModel );
                }else if( $this->selectedModel->payment_method->slug == "flutterwave" ){
                    $this->verifyFlutterwaveTransaction( $this->selectedModel, $this->transaction_id );
                }
                $this->error = false;
            }catch(\Exception $ex){
                $this->error = true;
                $this->errorMessage = $ex->getMessage();
            }
            return view('livewire.payment.callback')->layout('layouts.auth');

        }

    }



}
