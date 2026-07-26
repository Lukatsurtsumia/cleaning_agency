@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'options' => [],
    'placeholder' => null,
    'rows' => 4,
    'value' => null,
])

@php
    $id = 'field-'.$name;
    $current = old($name, $value);
    $hasError = $errors->has($name);

    $control = 'block w-full rounded-xl border-azur-900/15 bg-white text-sm text-azur-900 shadow-sm
                placeholder:text-azur-900/35 focus:border-azur-600 focus:ring-azur-600'
        .($hasError ? ' border-red-400 focus:border-red-500 focus:ring-red-500' : '');

    // Autocomplete and other input-level attributes pass straight through.
    $passthrough = $attributes->except('class');
@endphp

<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="block text-sm font-medium text-azur-900">
        {{ $label }}
        @unless ($required)
            <span class="font-normal text-azur-800/40">({{ __('site.common.optional') }})</span>
        @endunless
    </label>

    <div class="mt-1.5">
        @if ($type === 'select')
            <select id="{{ $id }}" name="{{ $name }}" @required($required) {{ $passthrough }} class="{{ $control }}">
                <option value="">&mdash;</option>
                @foreach ($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected($current === (string) $optionValue)>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
        @elseif ($type === 'textarea')
            <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" @required($required)
                      placeholder="{{ $placeholder }}" {{ $passthrough }}
                      class="{{ $control }}">{{ $current }}</textarea>
        @else
            <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $current }}"
                   @required($required) placeholder="{{ $placeholder }}" {{ $passthrough }}
                   class="{{ $control }}">
        @endif
    </div>

    @error($name)
        <p class="mt-1.5 text-sm font-medium text-red-700">{{ $message }}</p>
    @enderror
</div>
