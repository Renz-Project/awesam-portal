@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
@endsection
@section('content')
@include('error')
 <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               
                <h5 class="card-title mb-0">Clients <button type="button" class="btn btn-success btn-icon waves-effect waves-light" title='New Client' data-bs-toggle="modal" data-bs-target="#newClient"><i class=" ri-add-box-line"></i></button>
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
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Search name, email, contact"
                        value="{{ request('search') }}">

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
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    // 📤 Export to Excel
    document.getElementById("exportExcel").addEventListener("click", function() {
        let table = document.getElementById("example");
        let wb = XLSX.utils.table_to_book(table, {sheet:"Clients"});
        XLSX.writeFile(wb, "clients.xlsx");
    });
</script>
@endsection
