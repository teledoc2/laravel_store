<div>

    <x-baseview title="Privacy Policy">

        <x-form action="savePrivacyPolicy" backPressed="$set('showPrivacy', false)">
            <div class="w-full md:w-4/5 lg:w-5/12">

                <div class="mb-4">
                    <x-label title="Privacy & Policy"/>
                </div>
                <div class="hidden ">
                    <x-input title="" name="privacyPolicy"  />
                </div>
                <textarea id="privacyPolicy"></textarea>
                <x-buttons.primary title="Save Changes" />

            <div>
        </x-form>

    </x-baseview>



</div>




