<div class="table-responsive">
    <table class="movement-table table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>Type</th>
                <th>Encoded By</th>
                <th>Date</th>
                <th>Remarks</th>
                <th>Qty</th>
                @if(auth()->user()->role == 'Super Admin')
                    <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp

            @forelse($stockMovements as $m)
                <tr>
                    <td>{{ $m->type }}</td>
                    <td>{{ optional($m->user)->name }}</td>
                    <td>{{ date('M d, Y', strtotime($m->created_at)) }}</td>
                    <td>{{ $m->remarks }}</td>
                    <td class="{{ $m->type == 'inflow' ? 'text-success' : 'text-danger' }}">
                        {{ number_format($m->quantity, 2) }}
                    </td>
                    @if(auth()->user()->role == 'Super Admin')
                        <td>
                            <button class="btn btn-warning btn-sm editMovementBtn"
                                data-id="{{ $m->id }}"
                                data-type="{{ $m->type }}"
                                data-remarks="{{ $m->remarks }}"
                                data-quantity="{{ $m->quantity }}"
                                data-product="{{ $m->office_supply_id }}"
                                data-location="{{ $m->location_id }}">
                                Edit
                            </button>
                        </td>
                    @endif
                </tr>

                @php $total += $m->type == 'inflow' ? $m->quantity : -$m->quantity; @endphp
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->role == 'Super Admin' ? 6 : 5 }}" class="text-center">
                        No stock movements found.
                    </td>
                </tr>
            @endforelse

            <tr>
                <td colspan="4" class="text-end fw-bold">Total</td>
                <td class="fw-bold">{{ number_format($total, 2) }}</td>
                @if(auth()->user()->role == 'Super Admin')
                    <td></td>
                @endif
            </tr>
        </tbody>
    </table>
</div>
