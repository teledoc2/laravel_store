<?php

namespace App\Http\Livewire;

use App\Traits\SystemUpdateTrait;

class UpgradeLivewire extends BaseLivewireComponent
{

    use SystemUpdateTrait;
    public $terminalCommand;
    public $terminalError;
    public $phone_code;


    public function render()
    {
        return view('livewire.settings.upgrade');
    }


    public function upgradeAppSystem(){

        try{
            //
            $this->isDemo();
            $this->runUpgradeAppSystemCommands();
            $this->showSuccessAlert("System Updated Successfully!");
        }catch(\Exception $ex){
            $this->showErrorAlert( $ex->getMessage() ?? "Failed");
        }


    }


    public function runTerminalCommand(){

        $this->terminalError = "";

        try{

            //
            $this->isDemo();
            $this->systemTerminalRun( $this->terminalCommand );
            $this->showSuccessAlert("Terminal command successfully!");
        }catch(\Exception $error){
            $this->terminalError = $error->getMessage() ?? "Terminal command failed!";
        }

    }






}
