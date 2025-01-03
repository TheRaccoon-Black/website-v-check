@props(['label' => null, 'name', 'id', 'required'])

@if ($label)
    <label for="{{ $id }}"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</label>
@endif
<select
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
    name="{{ $name }}" id="{{ $id }}" required="{{ $required }}">
    @if ($label)
        <option selected class="text-gray-400" value="">Pilih {{ $label }}</option>
    @endif
    {{ $slot }}
</select>
