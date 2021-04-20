<?php

use App\Http\Livewire\Auth\LoginLivewire;
use App\Http\Livewire\Auth\PasswordResetLivewire;
use App\Http\Livewire\Auth\ForgotPasswordLivewire;

use App\Http\Livewire\BannerLivewire;
use App\Http\Livewire\DashboardLivewire;
use App\Http\Livewire\CategoryLivewire;
use App\Http\Livewire\VendorLivewire;
use App\Http\Livewire\ProductLivewire;
use App\Http\Livewire\FavouriteLivewire;
use App\Http\Livewire\ReviewLivewire;
use App\Http\Livewire\OptionGroupLivewire;
use App\Http\Livewire\MenuLivewire;
use App\Http\Livewire\OptionLivewire;

use App\Http\Livewire\OrderLivewire;
use App\Http\Livewire\CouponLivewire;
use App\Http\Livewire\DeliveryAddressLivewire;

use App\Http\Livewire\CurrencyLivewire;
use App\Http\Livewire\AppSettingsLivewire;
use App\Http\Livewire\SettingsLivewire;
use App\Http\Livewire\PaymentMethodivewire;
use App\Http\Livewire\Payment\OrderPaymentLivewire;
use App\Http\Livewire\Payment\OrderPaymentCallbackLivewire;

use App\Http\Livewire\PackageTypeLivewire;
use App\Http\Livewire\PackageTypePricingLivewire;
use App\Http\Livewire\CountryLivewire;
use App\Http\Livewire\StateLivewire;
use App\Http\Livewire\CitiesLivewire;
use App\Http\Livewire\VendorCitiesLivewire;

use App\Http\Livewire\UserLivewire;
use App\Http\Livewire\DriverEarningLivewire;
use App\Http\Livewire\VendorEarningLivewire;
use App\Http\Livewire\PayoutLivewire;

use App\Http\Livewire\BackUpLivewire;
use App\Http\Livewire\NotificationLivewire;
use App\Http\Livewire\TranslationLivewire;
use App\Http\Livewire\ImportLivewire;
use App\Http\Livewire\UpgradeLivewire;

use App\Http\Livewire\Payment\WalletTopUpLivewire;
use App\Http\Livewire\Payment\WalletTopUpCallbackLivewire;

use App\Http\Livewire\ProfileLivewire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use League\CommonMark\CommonMarkConverter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web']], function () {

    // Auth
    Route::get('login', LoginLivewire::class)->name('login');
    Route::get('logout', function(){
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');

    Route::get('password/forgot', ForgotPasswordLivewire::class)->name('password.forgot');
    Route::get('password/update/{code}/{email}', PasswordResetLivewire::class)->name('password.reset.link');


    // Pages
    Route::get('privacy/policy', function(){
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $html = $converter->convertToHtml( setting('privacyPolicy', "") );
        return view('layouts.pages.privacy', compact('html'));

    })->name('privacy');

    // AUth routes
    Route::group(['middleware' => ['auth']], function () {

        //
        Route::get('profile', ProfileLivewire::class)->name('profile');
        Route::get('', DashboardLivewire::class)->name('dashboard');
        Route::get('product/products', ProductLivewire::class)->name('products');
        Route::get('product/menus', MenuLivewire::class)->name('products.menus');
        Route::get('product/options/group', OptionGroupLivewire::class)->name('products.options.group');
        Route::get('product/options', OptionLivewire::class)->name('products.options');

        Route::get('order/orders', OrderLivewire::class)->name('orders');
        Route::get('coupons', CouponLivewire::class)->name('coupons');

        Route::get('vendors', VendorLivewire::class)->name('vendors');


        //admin routes
        Route::group(['middleware' => ['role:admin']], function () {

            //
            Route::get('banners', BannerLivewire::class)->name('banners');
            Route::get('categories', CategoryLivewire::class)->name('categories');
            Route::get('product/favourites', FavouriteLivewire::class)->name('favourites');
            Route::get('reviews', ReviewLivewire::class)->name('reviews');
            Route::get('order/delivery/addresses', DeliveryAddressLivewire::class)->name('delivery.addresses');

            Route::get('users', UserLivewire::class)->name('users');
            Route::get('backup', BackUpLivewire::class)->name('backups');
            //
            Route::get('setting/currencies', CurrencyLivewire::class)->name('currencies');
            Route::get('setting/settings', SettingsLivewire::class)->name('settings');
            Route::get('setting/app/settings', AppSettingsLivewire::class)->name('settings.app');
            Route::get('setting/payment/methods', PaymentMethodivewire::class)->name('payment.methods');
            Route::get('setting/translation', TranslationLivewire::class)->name('translation');
            Route::get('setting/upgrade', UpgradeLivewire::class)->name('upgrade');
            Route::get('notification/send', NotificationLivewire::class)->name('notification.send');

            //package
            Route::get('package/types', PackageTypeLivewire::class)->name('package.types');
            Route::get('package/countries', CountryLivewire::class)->name('package.countries');
            Route::get('package/states', StateLivewire::class)->name('package.states');
            Route::get('package/cities', CitiesLivewire::class)->name('package.cities');
            Route::get('earnings/vendors', VendorEarningLivewire::class)->name('earnings.vendors');
            Route::get('earnings/drivers', DriverEarningLivewire::class)->name('earnings.drivers');
            Route::get('payouts', PayoutLivewire::class)->name('payouts');

            //imports
            Route::get('imports', ImportLivewire::class)->name('imports');



        });

        //manager routes
        Route::group(['middleware' => ['role:manager']], function () {

            Route::get('package/pricing', PackageTypePricingLivewire::class)->name('package.pricing');
            Route::get('package/my/cities', VendorCitiesLivewire::class)->name('package.cities.my');

        });

    });



    //Unauth routes
    Route::get('order/payment', OrderPaymentLivewire::class)->name('order.payment');
    Route::get('order/payment/callback', OrderPaymentCallbackLivewire::class)->name('payment.callback');
    //Wallet
    Route::get('wallet/topup', WalletTopUpLivewire::class)->name('wallet.topup');
    Route::get('wallet/topup/callback', WalletTopUpCallbackLivewire::class)->name('wallet.topup.callback');


});
