@props([
    'name',
    'id' => null,
    'value' => '',
    'required' => false,
    'placeholder' => 'DD-MM-AAAA',
    'autoSubmit' => false,
    'class' => 'w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold',
])

@php
    $inputId = $id ?? ('date_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name));
    $displayVal = \App\Rules\SpanishDate::toSpanish((string) $value);
@endphp

<input
    type="text"
    name="{{ $name }}"
    id="{{ $inputId }}"
    value="{{ $displayVal }}"
    placeholder="{{ $placeholder }}"
    maxlength="10"
    inputmode="numeric"
    pattern="\d{2}[-/]\d{2}[-/]\d{4}"
    title="Formato obligatorio: Día-Mes-Año (DD-MM-AAAA)"
    {{ $required ? 'required' : '' }}
    oninput="
        let v = this.value.replace(/\D/g, '').slice(0, 8);
        if (v.length >= 5) {
            this.value = v.slice(0,2) + '-' + v.slice(2,4) + '-' + v.slice(4);
        } else if (v.length >= 3) {
            this.value = v.slice(0,2) + '-' + v.slice(2);
        } else {
            this.value = v;
        }
    "
    @if($autoSubmit)
    onchange="if(/^\d{2}-\d{2}-\d{4}$/.test(this.value)){ this.form.submit(); }"
    @endif
    {{ $attributes->merge(['class' => $class]) }}
>
