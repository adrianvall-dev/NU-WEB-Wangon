@props([
    'value' => 0,
    'start' => 0,
    'duration' => null,
    'delay' => null,
    'decimalPlaces' => 0,
    'prefix' => '',
    'suffix' => '',
    'format' => true,
    'class' => '',
])

@php
    $numericVal = is_numeric($value) ? (float) $value : 0;
    $formattedVal = $format ? number_format($numericVal, $decimalPlaces, ',', '.') : $numericVal;
@endphp

<span {{ $attributes->merge(['class' => 'number-ticker ' . $class]) }}
      data-number-ticker="{{ $numericVal }}"
      @if($start) data-start="{{ $start }}" @endif
      @if($duration) data-duration="{{ $duration }}" @endif
      @if($delay) data-delay="{{ $delay }}" @endif
      @if($decimalPlaces) data-decimal-places="{{ $decimalPlaces }}" @endif
      @if($prefix) data-prefix="{{ $prefix }}" @endif
      @if($suffix) data-suffix="{{ $suffix }}" @endif
      aria-label="{{ $prefix . $formattedVal . $suffix }}">
    <span class="number-ticker-sr-only">{{ $prefix . $formattedVal . $suffix }}</span>
    <span class="number-ticker-display" aria-hidden="true">{{ $prefix . $formattedVal . $suffix }}</span>
</span>
