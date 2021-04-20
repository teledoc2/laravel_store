@section('title', 'Orders')
    <div>

        <x-baseview title="Orders">
            <livewire:tables.order-table />
        </x-baseview>

        {{-- details moal --}}
        <div x-data="{ open: @entangle('showDetails') }">
            <x-modal-lg>

                <p class="text-xl font-semibold">Order Details</p>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <x-details.item title="Code" text="#{{ $selectedModel->code ?? '' }}" />
                    <x-details.item title="Status" text="{{ $selectedModel->status ?? '' }}" />
                    <x-details.item title="Payment Status" text="{{ $selectedModel->payment_status ?? '' }}" />
                    <x-details.item title="Payment Method" text="{{ $selectedModel->payment_method->name ?? '' }}" />
                </div>
                <div class="grid grid-cols-1 gap-4 mt-5 border-t md:grid-cols-2 lg:grid-cols-3">
                    <x-details.item title="{{ $selectedModel != null && $selectedModel->is_package ? 'Sender' : 'User' }}"
                        text="{{ $selectedModel->user->name ?? '' }}" />
                    <x-details.item
                        title="{{ $selectedModel != null && $selectedModel->is_package ? 'Sender Phone' : 'User Phone' }}"
                        text="{{ $selectedModel->user->phone ?? '' }}" />

                    {{-- Pickup address --}}
                    @if ($selectedModel != null && $selectedModel->is_package)
                        <x-details.item title="Pickup Address"
                            text="{{ $selectedModel->pickup_location->address ?? '' }}" />
                    @else
                        <x-details.item title="Delivery Address"
                            text="{{ $selectedModel->delivery_address->address ?? '' }}" />
                    @endif

                </div>
                {{-- recipient address/info --}}
                @if ($selectedModel != null && $selectedModel->is_package)
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <x-details.item title="Recipient" text="{{ $selectedModel->recipient_name ?? '' }}" />
                        <x-details.item title="Recipient Phone" text="{{ $selectedModel->recipient_phone ?? '' }}" />

                        {{-- Dropoff address --}}
                        <x-details.item title="Dropoff Address"
                            text="{{ $selectedModel->dropoff_location->address ?? '' }}" />

                    </div>
                @endif
                <div class="mt-5 border-t">
                    <x-details.item title="Note" text="{{ $selectedModel->note ?? '--' }}" />
                </div>
                <div class="grid grid-cols-1 gap-4 mt-5 border-t md:grid-cols-2 lg:grid-cols-3">

                    <x-details.item title="Vendor" text="{{ $selectedModel->vendor->name ?? '' }}" />
                    <x-details.item title="Vendor Address" text="{{ $selectedModel->vendor->address ?? '' }}" />


                    <x-details.item title="Date of order" text="{{ $selectedModel->formatted_date ?? '' }}" />
                    <x-details.item title="Updated At" text="{{ $selectedModel->updated_at ?? '--' }}" />
                </div>

                {{-- driver info --}}
                <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2 lg:grid-cols-3">
                    <x-details.item title="Driver" text="{{ $selectedModel->driver->name ?? '--' }}" />
                    <x-details.item title="Driver Phone" text="{{ $selectedModel->driver->phone ?? '--' }}" />
                </div>

                {{-- foods --}}
                @if ($selectedModel != null && $selectedModel->is_package)
                    <div class="pt-4 mt-4 border-t ">
                        <x-order.package :order="$selectedModel ?? ''" />
                    </div>
                @else
                    <div class="pt-4 mt-4 border-t ">
                        <x-order.products :products="$selectedModel->products ?? ''" />
                    </div>
                @endif

                {{-- money --}}
                <div class="pt-4 border-t justify-items-end">
                    <div class="flex items-center justify-end space-x-20 border-b">
                        <x-label title="Subtotal" />
                        <div class="w-6/12 md:w-4/12 lg:w-2/12">
                            <x-details.p text="{{ setting('currency', '$') }}{{ $selectedModel->sub_total ?? '' }}" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-20 border-b">
                        <x-label title="Discount Amount" />
                        <div class="w-6/12 md:w-4/12 lg:w-2/12">
                            <x-details.p text="-{{ setting('currency', '$') }}{{ $selectedModel->discount ?? '' }}" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-20 border-b">
                        <x-label title="Delivery Fee" />
                        <div class="w-6/12 md:w-4/12 lg:w-2/12">
                            <x-details.p
                                text="+{{ setting('currency', '$') }}{{ $selectedModel->delivery_fee ?? '' }}" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-20 border-b">
                        <x-label title="Tax" />
                        <div class="w-6/12 md:w-4/12 lg:w-2/12">
                            <x-details.p text="+{{ setting('currency', '$') }}{{ $selectedModel->tax ?? '' }}" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-20 border-b">
                        <x-label title="Total" />
                        <div class="w-6/12 md:w-4/12 lg:w-2/12">
                            <x-details.p text="{{ setting('currency', '$') }}{{ $selectedModel->total ?? '' }}" />
                        </div>
                    </div>
                </div>

            </x-modal-lg>
        </div>

        {{-- edit moal --}}
        <div x-data="{ open: @entangle('showEdit') }">
            <x-modal confirmText="Update" action="update">

                <p class="text-xl font-semibold">Edit Order</p>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-details.item title="Code" text="#{{ $selectedModel->code ?? '' }}" />
                    <x-details.item title="Status" text="{{ $selectedModel->status ?? '' }}" />
                    <x-details.item title="Payment Status" text="{{ $selectedModel->payment_status ?? '' }}" />
                    <x-details.item title="Payment Method" text="{{ $selectedModel->payment_method->name ?? '' }}" />
                </div>
                <div class="gap-4 mt-5 border-t">
                    <x-select title="Delivery Boy" :options="$deliveryBoys" name="deliveryBoyId" :noPreSelect="true" />
                    <x-select title="Status" :options="$orderStatus" name="status" />
                    <x-select title="Payment Status" :options="$orderPaymentStatus" name="paymentStatus" />
                    <x-input title="Note" name="note" />

                </div>
            </x-modal>
        </div>


    </div>
