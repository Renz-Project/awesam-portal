 <div class="modal fade" id="inventory{{$key}}" tabindex="-1" aria-labelledby="#inventory{{$key}}Label">
    <div class="modal-dialog modal-lg">
        <div class="modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success" id="inventory{{$key}}Label">Stock Movement ({{ $row['product_name'] }}) - {{ $row['location'] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @php
                    $total = 0;
                @endphp
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="example table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Encoded By</th>
                                    <th>Date</th>
                                    
                                    <th>Remarks</th>
                                    
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($row['stockMovements'] as $transaction)
                                <tr>
                                    <td>{{$transaction->type}}</td>
                                  
                                    <td>{{$transaction->user->name}}</td>
                                    <td>{{date('M d, Y',strtotime($transaction->created_at))}}</td>
                                        <td>{{$transaction->remarks}}</td>
                                          <td> <span @if($transaction->type == "inflow")class='text-success' @else class='text-danger' @endif>{{number_format($transaction->quantity,2)}}</span></td>
                                
                                </tr>
                                @if($transaction->type == "inflow")
                                @php
                                    $total = $total + $transaction->quantity;
                                @endphp
                                @else
                                @php
                                    $total = $total - $transaction->quantity;
                                @endphp
                                @endif
                                @endforeach
                                @foreach($row['transactions'] as $transaction)
                                <tr>
                                    <td>outflow</td>
                                
                                    <td>{{$transaction->user->name}}</td>
                                    <td>{{date('M d, Y',strtotime($transaction->created_at))}}</td>
                                    <td>{{$transaction->remarks}} <br>
                                        Client : {{$transaction->client->last_name}},{{$transaction->client->first_name}}
                                    </td>
                                    
                                    <td> <span class='text-danger'>{{number_format($transaction->qty,2)}}</span></td>
                                </tr>
                                @php
                                    $total = $total - $transaction->qty;
                                @endphp
                                @endforeach

                                 <tr>
                                    <td colspan='4' class='text-right'>Total</td>
                                    <td>{{number_format($total,2)}}
                                    </td>
                                </tr>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>