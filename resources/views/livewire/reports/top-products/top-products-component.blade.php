<div>
    @section('content_header_title', 'Barang Terlaris')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Barang Terlaris</h3>
            {{-- TODO: cetak --}}
            {{-- <div class="card-tools"></div> --}}
        </div>
        <div class="card-body">
            <livewire:reports.top-products.top-products-table />
        </div>
    </div>
</div>
