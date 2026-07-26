<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Booking #{{ $booking->id }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $booking->name }}</dd></div>
                    <div><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ $booking->email }}</dd></div>
                    <div><dt class="text-gray-500">Phone</dt><dd class="font-medium">{{ $booking->phone }}</dd></div>
                    <div><dt class="text-gray-500">Address</dt><dd class="font-medium">{{ $booking->address ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">Property type</dt><dd class="font-medium">{{ ucfirst($booking->property_type) }}</dd></div>
                    <div><dt class="text-gray-500">Service</dt><dd class="font-medium">{{ ucfirst($booking->service_type) }}</dd></div>
                    <div><dt class="text-gray-500">Bedrooms / Bathrooms</dt><dd class="font-medium">{{ $booking->bedrooms }} / {{ $booking->bathrooms }}</dd></div>
                    <div><dt class="text-gray-500">Extras</dt><dd class="font-medium">{{ $booking->extras ? collect($booking->extras)->map(fn ($e) => ucfirst($e))->join(', ') : 'None' }}</dd></div>
                    <div><dt class="text-gray-500">Dates</dt><dd class="font-medium"><x-booking-dates :booking="$booking"/></dd></div>
                    <div><dt class="text-gray-500">Estimated price</dt><dd class="font-medium">{{ number_format($booking->estimated_price, 2) }} &euro;</dd></div>
                </dl>

                @if ($booking->notes)
                    <div class="mt-6">
                        <dt class="text-gray-500 text-sm">Notes</dt>
                        <dd class="mt-1">{{ $booking->notes }}</dd>
                    </div>
                @endif

                <div class="mt-6 flex items-center gap-4">
                    <a href="{{ route('booking.pdf', $booking) }}" class="text-sm font-medium text-teal-700 hover:underline">Download PDF</a>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Update Status</h3>
                <form method="POST" action="{{ route('booking.status', $booking) }}" class="flex items-center gap-4">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="rounded-md border-gray-300 shadow-sm text-sm">
                        @foreach (['new', 'contacted', 'scheduled', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700">
                        Save
                    </button>
                </form>
            </div>

            <a href="{{ route('booking.index') }}" class="text-sm font-medium text-teal-700 hover:underline">&larr; Back to bookings</a>
        </div>
    </div>
</x-app-layout>
