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

            @foreach($stockMovements as $m)
                <tr>
                    <td>{{ $m->type }}</td>
                    <td>{{ $m->user->name }}</td>
                    <td>{{ date('M d, Y', strtotime($m->created_at)) }}</td>
                    <td>{{ $m->remarks }}</td>
                    <td class="{{ $m->type == 'inflow' ? 'text-success' : 'text-danger' }}">
                        {{ number_format($m->quantity,2) }}
                    </td>
                    @if(auth()->user()->role == 'Super Admin')
                        <td>
                            <button class="btn btn-warning btn-sm editMovementBtn"
                                data-id="{{ $m->id }}"
                                data-type="{{ $m->type }}"
                                data-remarks="{{ $m->remarks }}"
                                data-quantity="{{ $m->quantity }}">
                                Edit
                            </button>
                        </td>
                    @endif
                </tr>

                @php $total += $m->type == 'inflow' ? $m->quantity : -$m->quantity; @endphp
            @endforeach

            @foreach($transactions as $t)
                <tr>
                    <td>outflow</td>
                    <td>{{ $t->user->name }}</td>
                    <td>{{ date('M d, Y', strtotime($t->created_at)) }}</td>
                    <td>
                        {{ $t->remarks }} <br>
                        Client: {{ $t->client->last_name }}, {{ $t->client->first_name }}
                    </td>
                    <td class="text-danger">{{ number_format($t->qty,2) }}</td>
                    @if(auth()->user()->role == 'Super Admin')
                        <td>
                            <button class="btn btn-warning btn-sm editMovementBtn"
                                data-id="{{ $t->id }}"
                                data-type="outflow"
                                data-remarks="{{ $t->remarks }}"
                                data-quantity="{{ $t->qty }}">
                                Edit
                            </button>
                        </td>
                    @endif
                </tr>

                @php $total -= $t->qty; @endphp
            @endforeach

            <tr>
                <td colspan="5" class="text-end fw-bold">Total</td>
                <td class="fw-bold">{{ number_format($total,2) }}</td>
            </tr>
        </tbody>
    </table>
</div>
