 <div class="modal fade" id="FdoInventory{{$key}}" tabindex="-1" aria-labelledby="#DAInventory{{$key}}Label">
    <div class="modal-dialog modal-lg">
        <div class="modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success" id="DAInventory{{$key}}Label">FDO Low Inventory ({{$location['location_name']}})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="example table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Ideal Stock</th>
                                    <th>Available Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockReportFdos->where('location_id',$location['location_id']) as $key => $row)
                                <tr class="">
                                    <td>{{ $row['product_code'] }}</td>
                                    <td>{{ $row['product_name'] }}</td>
                                    <td>{{ $row['ideal_stock'] }}</td>
                                    <td>{{ $row['available_stock'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>