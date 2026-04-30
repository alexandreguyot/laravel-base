{{-- Rétrocompatibilité — redirige vers x-ui.alert --}}
@props(['message' => '', 'variant' => 'info'])

@php
$v = match($variant) {
    'indigo', 'blue', 'info'                    => 'info',
    'green', 'success', 'emerald', 'teal'       => 'success',
    'yellow', 'orange', 'amber', 'warning'      => 'warning',
    'red', 'rose', 'pink', 'danger', 'error'    => 'error',
    default                                      => 'info',
};
@endphp

<x-ui.alert :variant="$v" dismissible>{{ $message }}</x-ui.alert>
