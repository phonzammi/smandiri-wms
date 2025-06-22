<div>
    @section('content_header_title', 'Insentif Sales')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Data Sales</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Sales</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No Hp.</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salesman as $salesmen)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $salesmen->name }}</td>
                                <td>{{ $salesmen->address }}</td>
                                <td>{{ $salesmen->phone }}</td>
                                <td>
                                    <a href="{{ route('salesman-incentive.show', $salesmen) }}"
                                        class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr class="table-danger">
                                <td colspan="5">Tidak ada Sales</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
