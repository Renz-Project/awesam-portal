@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
@include('error')
 <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               
                <h5 class="card-title mb-0">Clients <button type="button" class="btn btn-success btn-icon waves-effect waves-light" title='New Client' data-bs-toggle="modal" data-bs-target="#newClient"><i class=" ri-add-box-line"></i></button>
                @if(auth()->user()->role == 'Super Admin')
                <button type="button" class="btn btn-warning btn-icon waves-effect waves-light" title="Merge Duplicate Clients" data-bs-toggle="modal" data-bs-target="#mergeClients"><i class="ri-git-merge-line"></i></button>
                @endif
                <form method="GET" action="{{ url('clients') }}" class="d-flex align-items-center">
                    <!-- Location Filter -->
                    <select name="location_id" class="form-select form-select-sm me-2">
                        <option value="">All Locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Search Field -->
                    <input type="text" name="last_name" class="form-control form-control-sm me-2"
                        placeholder="Search Last Name"
                        value="{{ request('search') }}">
                        <input type="text" name="first_name" class="form-control form-control-sm me-2"
                        placeholder="Search First Name"
                        value="{{ request('first_name') }}">

                    <button type="submit" class="btn btn-primary btn-sm me-2">Search</button>
                    <a href="{{ url('clients') }}" class="btn btn-secondary btn-sm me-2">Reset</a>

                    <!-- Export Button -->
                    {{-- <button type="button" id="exportExcel" class="">Export Excel</button> --}}
                    @if(auth()->user()->role == 'Super Admin')
                     <a href="{{ url('clients/export') . '?' . http_build_query(request()->query()) }}"
                        class="btn btn-success btn-sm">
                        Export
                    </a>
                    @endif
                </form>
                </h5>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Birth Date</th>
                            <th>Gender</th>
                            <th>Last Transaction</th>
                            <th>Attachments</th>
                            <th>Locations</th>
                            @if(auth()->user()->role == 'Super Admin')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                <a href="{{ url('client/' . $client->id) }}">
                                    <img src="{{ $client->avatar }}" 
                                         onerror="this.src='{{ URL::asset('/images/aaa.png') }}';"
                                         alt="" class="avatar-xs rounded-circle me-2 material-shadow">
                                    {{ $client->last_name }}, {{ $client->first_name }}
                                </a>
                            </td>
                            <td>@if($client->birth_date){{date('M d, Y',strtotime($client->birth_date))}}@endif</td>
                            <td>{{$client->sex}}</td>
                            <td>
                                @if($client->transactions->count() > 0)
                                    {{ date('d M, Y', strtotime($client->transactions->sortByDesc('created_at')->first()->created_at)) }}
                                @else
                                    No transaction yet
                                @endif
                            </td>
                            <td>
                                @forelse($client->attachments as $attachment)
                                    <div>
                                        <a href="{{ url('view-attachment/'.$attachment->id) }}" target="_blank">
                                            {{ $attachment->file_name ?? 'Attachment' }}
                                        </a>
                                    </div>
                                @empty
                                    No attachments yet
                                @endforelse
                            </td>
                            <td>
                                @foreach($client->locations as $location)
                                    {{ $location->name }} <br>
                                @endforeach
                            </td>
                            @if(auth()->user()->role == 'Super Admin')
                                <td>
                                    <button type="button"
                                        class="btn btn-warning btn-sm merge-client-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#mergeClients"
                                        data-client-id="{{ $client->id }}"
                                        data-client-name="#{{ $client->id }} - {{ $client->last_name }}, {{ $client->first_name }}">
                                        <i class="ri-git-merge-line"></i> Merge
                                    </button>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- 🔹 Pagination Links -->
                <div class="mt-3">
                    {{ $clients->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@include('clients.new_client')
@if(auth()->user()->role == 'Super Admin')
    @include('clients.merge_clients')
@endif
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    // 📤 Export to Excel
    const exportExcel = document.getElementById("exportExcel");
    if (exportExcel) {
        exportExcel.addEventListener("click", function() {
        let table = document.getElementById("example");
        let wb = XLSX.utils.table_to_book(table, {sheet:"Clients"});
        XLSX.writeFile(wb, "clients.xlsx");
        });
    }
    function formatClient(client) {
        if (client.loading) {
            return client.text;
        }

        let birthDate = client.birth_date ? ' - ' + client.birth_date : '';
        return '#' + client.id + ' - ' + client.last_name + ', ' + client.first_name + birthDate;
    }

    function initClientMergeSelect(selector) {
        $(selector).select2({
            dropdownParent: $('#mergeClients'),
            placeholder: 'Search client...',
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('clients.search') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        selectedLocation: $('select[name="location_id"]').val()
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (client) {
                            return {
                                id: client.id,
                                text: '#' + client.id + ' - ' + client.last_name + ', ' + client.first_name,
                                first_name: client.first_name,
                                last_name: client.last_name,
                                birth_date: client.birth_date
                            };
                        })
                    };
                }
            },
            templateResult: formatClient,
            templateSelection: function (client) {
                return client.text || formatClient(client);
            }
        });
    }

    initClientMergeSelect('#primary_client_id');
    initClientMergeSelect('#duplicate_client_id');

    $('.merge-client-btn').on('click', function () {
        const clientId = $(this).data('client-id');
        const clientName = $(this).data('client-name');
        const option = new Option(clientName, clientId, true, true);

        $('#duplicate_client_id').empty().append(option).trigger('change');
    });

    $('#mergeClients').on('hidden.bs.modal', function () {
        $('#primary_client_id').val(null).trigger('change');
        $('#duplicate_client_id').val(null).trigger('change');
    });

    $('#mergeClientsForm').on('submit', function () {
        const primaryClient = $('#primary_client_id').val();
        const duplicateClient = $('#duplicate_client_id').val();

        if (primaryClient === duplicateClient) {
            alert('Please choose two different clients.');
            return false;
        }

        const confirmed = confirm('Merge duplicate client into the client to keep? This will move transactions, attachments, and locations, then delete the duplicate client.');

        if (confirmed && typeof show === 'function') {
            show();
        }

        return confirmed;
    });
</script>
@endsection
