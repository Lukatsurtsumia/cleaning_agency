<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Booking Requests</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-md bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($feedUrl)
                <div class="mb-6 rounded-lg bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">Agenda (.ics)</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Abonnez votre application d'agenda à ce lien pour voir les interventions confirmées.
                    </p>
                    <input type="text" readonly value="{{ $feedUrl }}" onclick="this.select()"
                           class="mt-3 block w-full rounded-md border-gray-300 bg-gray-50 text-sm text-gray-700">
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase text-xs">Customer</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase text-xs">Service</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase text-xs">Dates</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase text-xs">Estimate</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase text-xs">Status</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase text-xs">Requested</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $booking->name }}</div>
                                    <div class="text-gray-500">{{ $booking->email }}</div>
                                </td>
                                <td class="px-6 py-4">{{ ucfirst($booking->service_type) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                    <x-booking-dates :booking="$booking"/>
                                </td>
                                <td class="px-6 py-4">{{ number_format($booking->estimated_price, 2) }} &euro;</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $booking->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('booking.show', $booking) }}" class="text-teal-700 font-medium hover:underline">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">No booking requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
