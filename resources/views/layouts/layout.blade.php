@extends('adminlte::page')

{{-- Extend and customize the browser title --}}
@section('title')

    @if (isset($title))
        {{ $title }} |
    @else
        @hasSection('subtitle')
            @yield('subtitle') |
        @endif
    @endif
    {{ config('adminlte.title') }}
@endsection

@push('css')
    @vite(['resources/js/app.js'])
    <!-- Adds the Core Table Styles -->
    @rappasoftTableStyles
    <!-- Adds any relevant Third-Party Styles (Used for DateRangeFilter (Flatpickr) and NumberRangeFilter) -->
    {{-- @rappasoftTableThirdPartyStyles --}}
@endpush

{{-- Extend and customize the page content header --}}
@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@endsection

{{-- Rename section content to content_body --}}
@section('content')
    @yield('content_body', $slot ?? '')
@stop

{{-- Create a common footer --}}
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            Sistem Gudang Sukses Mandiri
        </a>
    </strong>
@stop

{{-- Add common Javascript/Jquery code --}}
@push('js')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            width: 'auto',
            showConfirmButton: false,
            showCloseButton: true,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        const SwalDialog = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-secondary"
            },
        });
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast-message', (event) => {
                Toast.fire({
                    icon: event.props.icon,
                    text: event.props.text,
                });
            });
        });
    </script>
    <!-- Adds the Core Table Scripts -->
    @rappasoftTableScripts
    <!-- Adds any relevant Third-Party Scripts (e.g. Flatpickr) -->
    {{-- @rappasoftTableThirdPartyScripts --}}
@endpush
