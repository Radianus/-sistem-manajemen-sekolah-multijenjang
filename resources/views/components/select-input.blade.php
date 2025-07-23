<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if ($label)
        <x-input-label for="{{ $id }}" :value="__($label)" />
    @endif

    <select id="{{ $id }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        {{ $required ? 'required' : '' }}
        class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        {{ $multiple ? 'multiple' : '' }} {{ $size ? 'size=' . $size : '' }}>

        {{-- Default option for single select --}}
        @if (!$multiple)
            <option value="">{{ $label ? 'Pilih ' . $label : 'Pilih opsi' }}</option>
        @endif

        @foreach ($options as $optionValue => $optionText)
            {{-- Handle associative arrays (value => text) and simple arrays (value, value) --}}
            @php
                $currentValue = is_array($options) && array_is_list($options) ? $optionText : $optionValue;
                $currentText = is_array($options) && array_is_list($options) ? $optionText : $optionText;
            @endphp
            <option value="{{ $currentValue }}"
                @if (is_array($selected)) {{ in_array($currentValue, $selected) ? 'selected' : '' }}
                    @else
                        {{ $currentValue == $selected ? 'selected' : '' }} @endif>
                {{ $currentText }}
            </option>
        @endforeach
    </select>

    @error($name)
        <x-input-error :messages="$message" class="mt-2" />
    @enderror
</div>
