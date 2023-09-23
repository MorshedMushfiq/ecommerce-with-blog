<x-adminheader/>
              {{-- orders --}}

              <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                        <p class="card-title mb-0">Our Orders</p>
    
                        @if(Session::has("success"))
                        <p class='alert alert-success my-2'>{{Session::get("success")}} <button class='close' data-dismiss="alert">&times;</button> </p>
    
                        @endif
                      
                      <div class="table-responsive">
                        <table style='overflow-x: visiable;' class="table table-striped table-borderless">
                          <thead>
                            <tr>
                              <th>#.</th>
                              <th>Customer</th>
                              <th>E-mail</th>
                              <th>Customer Status</th>
                              <th>Bill</th>
                              <th>Cell</th>
                              <th>Address</th>
                              <th>Order Date</th>
                              <th>Products</th>
                              <th>Order Status</th>
                              <th>Action</th>
                            </tr>  
                          </thead>
                          <tbody>
                            @php
                                $i=0;
                            @endphp

                            @foreach($all_order as $order)

                            @php
                                $i++;
                            @endphp
                            <tr>
                              <td>{{$i}}</td>
                              <td>{{$order->name}}</td>
                              <td class="font-weight-bold">{{$order->email}}</td>
                              <td class="font-weight-medium">{{$order->userStatus}}</td>
                              <td>{{$order->bill}}</td>
                              <td>{{$order->cell_number}}</td>
                              <td>{{$order->address}}</td>
                              <td>{{$order->created_at}}</td>
                              <td>
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updatemodal{{$i}}">
                                  Products
                                  </button>  
      
                                   <!-- The Modal -->
                          <div class="modal" id="updatemodal{{$i}}">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                          
                                      <!-- Modal Header -->
                                      <div class="modal-header">
                                      <h4 class="modal-title">Order Products</h4>
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                      </div>
                              
                                      <!-- Modal body -->
                                      <div class="modal-body">
                                          <table>
                                            <thead>
                                              <tr>
                                                <th>Product</th>
                                                <th>Image</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Sub Total</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              @foreach($order_item as $order_items)
                                              @if($order_items->orderId==$order->id)
                                              <tr>
                                                <td>{{$order_items->title}}</td>
                                                <td><img src="{{URL::to('uploads/products/'. $order_items->image)}}" alt=""></td>
                                                <td>{{$order_items->price}}</td>
                                                <td>{{$order_items->quantites}}</td>
                                                <td>{{$order_items->price * $order_items->quantites}}</td>
                                              </tr>
                                              @endif
                                              @endforeach
                                            </tbody>
                                          </table>
                                      </div>
                          
                                  </div>
                              </div>
                          </div>
    
                              </td>
                              <td>{{$order->status}}</td>
                              <td>
                                @if($order->status=="Paid")
                                <a href="{{URL::to('/dashboard/orders/Accept', $order->id)}}" class='btn btn-success btn-sm'>Accept</a>  
                                <a href="{{URL::to('/dashboard/orders/Reject', $order->id)}}" class='btn btn-danger btn-sm'>Reject</a> 
                                @elseif($order->status=="Accept")
                                <a href="{{URL::to('/dashboard/orders/Delievred', $order->id)}}" class='btn btn-success btn-sm'>Completed</a>
                                @elseif($order->status=="Delievred")
                                Already Accepted.
                                @else
                                <a href="{{URL::to('/dashboard/orders/Accept', $order->id)}}" class='btn btn-success btn-sm'>Accept</a>
                                @endif
                              </td>
    
    
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            
<x-adminfooter />



