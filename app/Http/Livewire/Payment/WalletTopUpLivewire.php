<?php

namespace App\Http\Livewire\Payment;

use App\Models\Order;
use App\Http\Livewire\BaseLivewireComponent;
use App\Models\PaymentMethod;
use App\Models\WalletTransaction;
use App\Traits\FlutterwaveTrait;
use App\Traits\PaystackTrait;
use App\Traits\RazorPayTrait;
use App\Traits\StripeTrait;

class WalletTopUpLivewire extends BaseLivewireComponent
{
    use StripeTrait, RazorPayTrait, PaystackTrait, FlutterwaveTrait;

    public $code;
    public $error;
    public $currency;
    public $paymentStatus;
    public $selectedPaymentMethod;
    protected $queryString = ['code'];


    public function mount(){
        $this->selectedModel = WalletTransaction::with('wallet.user', 'payment_method')->where('ref', $this->code)->first();
    }

    public function render()
    {
        //
        return view('livewire.payment.wallet', [
            "transaction" => $this->selectedModel,
            "paymentMethods" => PaymentMethod::topUp()->get(),
        ])->layout('layouts.auth');
    }

     //
     public function initPayment($id)
     {
        
        $this->selectedPaymentMethod = PaymentMethod::find($id);
         $paymentMethodSlug = $this->selectedPaymentMethod->slug;
 
         if ($paymentMethodSlug == "stripe") {
             $session = $this->createStripeTopupSession($this->selectedModel, $this->selectedPaymentMethod);
             $this->emit('initStripe', [
                 $this->selectedPaymentMethod->public_key,
                 $session,
             ]);
         } else if ($paymentMethodSlug == "razorpay") {
             //initialize razorpay payment order
             $razorpayOrderId = $this->createRazorpayTopupReference($this->selectedModel, $this->selectedPaymentMethod);
             //
             $this->emit('initRazorpay', [
                 $this->selectedPaymentMethod->public_key,
                 $this->selectedModel->amount * 100,
                 setting('currencyCode', 'INR'),
                 setting('websiteName', env("APP_NAME")),
                 setting('websiteLogo', asset('images/logo.png')),
                 $razorpayOrderId,
                 route('wallet.topup.callback', ["code" => $this->selectedModel->ref, "status" => "success"]),
             ]);
         } else if ($paymentMethodSlug == "paystack") {
             //initialize razorpay payment order
             $paymentRef = $this->createPaystackTopupReference($this->selectedModel, $this->selectedPaymentMethod);
             //
             $this->emit('initPaystack', [
                 $this->selectedPaymentMethod->public_key,
                 $this->selectedModel->wallet->user->email,
                 $this->selectedModel->amount * 100,
                 setting('currencyCode', 'USD'),
                 $paymentRef,
                 route('wallet.topup.callback', ["code" => $this->selectedModel->ref, "status" => "success"]),
             ]);
         } else if ($paymentMethodSlug == "flutterwave") {
             //initialize razorpay payment order
             $paymentRef = $this->createFlutterwaveTopupReference($this->selectedModel, $this->selectedPaymentMethod);
             //
             $this->emit('initFlwPayment', [
                 $this->selectedPaymentMethod->public_key,
                 $paymentRef,
                 $this->selectedModel->amount,
                 setting('currencyCode', 'USD'),
                 //country code
                 setting('currencyCountryCode', 'US'),
                 route('wallet.topup.callback', ["code" => $this->selectedModel->ref, "status" => "success"]),
                 //customer info
                 [
                     $this->selectedModel->wallet->user->email,
                     $this->selectedModel->wallet->user->phone,
                     $this->selectedModel->wallet->user->name,
                 ],
                 //company info
                 [
                     setting('websiteName', env("APP_NAME")),
                     setting('websiteLogo', asset('images/logo.png')),
                 ],
             ]);
         }
     }
 }

  
