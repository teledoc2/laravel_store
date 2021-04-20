<?php

namespace App\Http\Livewire\Payment;

use App\Models\Order;
use App\Http\Livewire\BaseLivewireComponent;
use App\Models\WalletTransaction;
use App\Traits\FlutterwaveTrait;
use App\Traits\PaystackTrait;
use App\Traits\RazorPayTrait;
use App\Traits\StripeTrait;

class WalletTopUpCallbackLivewire extends BaseLivewireComponent
{

    use StripeTrait, RazorPayTrait, PaystackTrait, FlutterwaveTrait;
    public $code;
    public $status;
    public $transaction_id;
    public $error;
    public $errorMessage;
    protected $queryString = ['code', 'status', 'transaction_id'];


    public function mount()
    {
        $this->selectedModel = WalletTransaction::with('wallet.user', 'payment_method')->where('ref', $this->code)->first();
         //
         if (empty($this->selectedModel)) {
            
        } else {

            try {
                if ($this->selectedModel->payment_method->slug == "stripe") {
                    $this->verifyStripeTopupTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "razorpay") {
                    $this->verifyRazorpayTopupTransaction( $this->selectedModel );
                } else if ($this->selectedModel->payment_method->slug == "paystack") {
                    $this->verifyPaystackTopupTransaction($this->selectedModel);
                } else if ($this->selectedModel->payment_method->slug == "flutterwave") {
                    $this->verifyFlutterwaveTopupTransaction($this->selectedModel, $this->transaction_id);
                }
                $this->error = false;
            } catch (\Exception $ex) {
                $this->error = true;
                $this->errorMessage = $ex->getMessage();
            }
        }
    }

    public function render()
    {

        //
        if (empty($this->selectedModel)) {
            return view('livewire.payment.invalid')->layout('layouts.auth');
        } else {

            return view('livewire.payment.wallet_callback')->layout('layouts.auth');
        }
    }
}
