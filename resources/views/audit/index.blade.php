@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
@include('error')
 <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Audit Logs</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('audit') }}" class="row g-3 align-items-end mb-3">
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">Filter by User</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">All Users</option>
                            <option value="system" {{ request('user_id') === 'system' ? 'selected' : '' }}>System</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('audit') }}" class="btn btn-light">Reset</a>
                    </div>
                </form>

                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                         <tr>
                            <th>User</th>
                            <th>Event</th>
                            <th>Model</th>
                            <th>Model ID</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                            <th>Date</th>
                            <th>Ip Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($audits as $audit)
                            <tr>
                                <td>{{ optional($audit->user)->name ?? 'System' }}</td>
                                <td>{{ ucfirst($audit->event) }}</td>
                                <td>{{ class_basename($audit->auditable_type) }}</td>
                                <td>{{ $audit->auditable_id }}</td>
                                <td>
                                    @foreach ((array) $audit->old_values as $key => $value)
                                        <div><strong>{{ $key }}:</strong> {{ $value }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ((array) $audit->new_values as $key => $value)
                                        <div><strong>{{ $key }}:</strong> {{ $value }}</div>
                                    @endforeach
                                </td>
                                <td>{{ date('Y-m-d H:i:s',strtotime($audit->created_at))}}</td>
                                <td>{{$audit->ip_address}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No audit records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $audits->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div><!--end col-->
</div><!--end row-->
@endsection
@section('js')
 <!-- gridjs js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

    <script src="{{asset('inside_css/assets/js/pages/datatables.init.js')}}"></script>
    <!-- App js -->
     <script src="{{asset('inside_css/assets/libs/prismjs/prism.js')}}"></script>
     <script>
        $('#example').DataTable({
            ordering: false,
            paging: false,
            searching: false,
            info: false
        });
</script>
@endsection
