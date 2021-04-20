<?php

namespace App\Traits;

use App\Models\PaymentMethod;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

trait SystemUpdateTrait
{

    public function runUpgradeAppSystemCommands()
    {
        //
        $appVersionCode = setting('appVerisonCode', "1");
        $appVerison = setting('appVerison', "1.0.0");


        if ($appVersionCode == 1) {

            $appVersionCode++;
            $appVerison = "1.0.0";
        }
        if ($appVersionCode == 2) {

            $appVersionCode++;
            $appVerison = "1.0.1";

            //drop category_id from products
            if (Schema::hasColumn('products', 'category_id')) {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropColumn('category_id');
                });
            }
        }
        if ($appVersionCode == 3) {

            $appVersionCode++;
            $appVerison = "1.0.2";

            //migrate the user tokens
            if (!Schema::hasTable('user_tokens')) {
                Artisan::call('migrate --force');
            }
        }

        if ($appVersionCode == 4) {

            $appVersionCode++;
            $appVerison = "1.0.3";

            //migrate users
            if (!Schema::hasColumn('users', 'is_online')) {

                Schema::table('users', function (Blueprint $table) {
                    $table->boolean('is_online')->default(false)->after("is_active");
                });
            }

            //migrate reviews
            if (!Schema::hasColumn('reviews', 'order_id')) {

                //sorry
                // Review::whereNotNull('id')->delete();
                Schema::table('reviews', function (Blueprint $table) {
                    $table->dropForeign(['vendor_id']);
                    $table->dropColumn('vendor_id');
                });

                Schema::table('reviews', function (Blueprint $table) {
                    $table->foreignId('vendor_id')->constrained()->nullable();
                    $table->foreignId('order_id')->constrained()->nullable();
                });
            }
        }


        if ($appVersionCode == 5) {

            $appVersionCode++;
            $appVerison = "1.1.0";

            if (!Schema::hasTable('wallet_transactions')) {
                Artisan::call('migrate --force');
            }

            //wallet payment method
            $paymetnMethod = PaymentMethod::where('slug', "wallet")->first();
            if (empty($paymetnMethod)) {
                \DB::table('payment_methods')->insert(array(
                    0 =>
                    array(
                        'name' => 'Wallet',
                        'slug' => 'wallet',
                        'is_active' => 1,
                        'is_cash' => 1,
                        'created_at' => '2021-01-09 12:38:10',
                        'updated_at' => '2021-01-09 12:38:10',
                    ),
                ));
            }
        }

        setting([
            'appVerisonCode' =>  $appVersionCode,
            'appVerison' =>  $appVerison,
        ])->save();
    }

    public function systemTerminalRun($command)
    {

        $commandArray = explode(" ", $command);
        $composerInstall = new Process($commandArray);
        $composerInstall->setWorkingDirectory(base_path());
        $composerInstall->run();

        if (!$composerInstall->isSuccessful()) {
            throw new ProcessFailedException($composerInstall);
        }
    }
}
