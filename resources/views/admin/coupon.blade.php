<x-adminheader/>
              {{-- orders --}}

              <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">

                        @if(Session::has("success"))
                        <p class='alert alert-success my-2'>{{Session::get("success")}} <button class='close' data-dismiss="alert">&times;</button> </p>
    
                        @endif
                        <p class="card-title mb-0">My Coupons</p>
                        <!-- Button to Open the Modal -->
                        <button type="button" class="btn btn-primary btn-sm my-2" data-toggle="modal" data-target="#addnewmodal">
                            Add New Coupon
                        </button>
    
                        <!-- The Modal -->
                        <div class="modal" id="addnewmodal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                        
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                    <h4 class="modal-title">Add New Coupon</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                            
                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <form action="{{route('coupon.upload')}}" method='POST' enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="">Coupon Name:</label>
                                                <input type="text" value="" name='name' placeholder='Coupon Name' class='form-control'>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Coupon Code:</label>
                                                <input type="text" value=""  name='code' placeholder='Coupon Code' class='form-control'>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Coupon Amount:</label>
                                                <input type="text" value=""  name='amount' placeholder='Coupon Amount' class='form-control'>
                                            </div>
    
                                            <div class="form-group">
                                                <input type="submit" value='Upload Coupon' class='btn btn-info'>
                                            </div>
                                        </form>
                                    </div>
                        
                                </div>
                            </div>
                        </div>
                      
                      <div class="table-responsive">
                        <table style='overflow-x: visiable;' class="table table-striped table-borderless">
                          <thead>
                            <tr>
                              <th>#.</th>
                              <th>Coupon name</th>
                              <th>Coupon Code</th>
                              <th>Coupon Amount</th>
                              <th>coupon status</th>
                              <th>Action</th>
                            </tr>  
                          </thead>
                          <tbody>
                            @php
                                $i=0;
                            @endphp

                            @foreach($all_coupons as $coupon)

                            @php
                                $i++;
                            @endphp
                            <tr>
                              <td>{{$i}}</td>
                              <td>{{$coupon->coupon_name}}</td>
                              <td><p>{{$coupon->coupon_code}}</p></td>
                              <td>{{$coupon->coupon_amount}}</td>
                              <td>{{$coupon->status}}</td>
                              <td>
                                <button type="button" class="btn btn-primary btn-sm my-2" data-toggle="modal" data-target="#addnewmodal{{$i}}">
                                    Edit your Coupon
                                </button>
            
                                <!-- The Modal -->
                                <div class="modal" id="addnewmodal{{$i}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                            <h4 class="modal-title">Edit Coupon</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                    
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="{{route('update.coupon', $coupon->id)}}" method='POST' enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="">Coupon Name:</label>
                                                        <input type="text" value="{{$coupon->coupon_name}}" name='name' placeholder='Coupon Name' class='form-control'>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Coupon Code:</label>
                                                        <input type="text" value="{{$coupon->coupon_code}}"  name='code' placeholder='Coupon Code' class='form-control'>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">Coupon Amount:</label>
                                                        <input type="text" value="{{$coupon->coupon_amount}}" name='amount' placeholder='Coupon Code' class='form-control'>
                                                    </div>
            
                                                    <div class="form-group">
                                                        <input type="submit" value='Update Coupon' class='btn btn-primary'>
                                                    </div>
                                                </form>
                                            </div>
                                
                                        </div>
                                    </div>
                                </div>
                                <a href="{{route('delete.coupon', $coupon->id)}}" class='btn btn-danger btn-sm'>Delete</a>
                                
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



