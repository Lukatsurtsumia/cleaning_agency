<x-site-layout>
    @if (session('status'))
        <div class="mx-auto max-w-3xl px-4 pt-6 sm:px-6">
            <div class="flex flex-col gap-2 rounded-2xl border border-azur-300 bg-azur-50 px-5 py-4 text-sm text-azur-900 sm:flex-row sm:items-center sm:justify-between">
                <p class="flex items-start gap-2.5">
                    <x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-azur-600"/>
                    {{ session('status') }}
                </p>

                @if (session('booking_pdf_url'))
                    <a href="{{ session('booking_pdf_url') }}"
                       class="shrink-0 font-semibold text-azur-700 underline underline-offset-2 hover:text-azur-900">
                        PDF
                    </a>
                @endif
            </div>
        </div>
    @endif

    @include('website.hero', ['services' => $services])
    @include('website.services', ['services' => $services])
    @include('website.pricing', ['pricing' => $pricing])
    @include('website.gallery-preview', ['galleries' => $galleries])
    @include('website.about')
        {{-- Map is its own section now, separate from the contact form. --}}
    @include('website.location')
    @include('website.contact')

</x-site-layout>
