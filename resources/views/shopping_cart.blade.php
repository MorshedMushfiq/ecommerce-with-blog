
<x-frontheader />


<section class="h-100 h-custom">
    <div class="container h-100 py-5">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col">
          @if(Session::has("success"))
          <p class='alert alert-success'>{{Session::get('success')}}</p>
          @endif
          @if(Session::has("error"))
          <p class='alert alert-danger'>{{Session::get('error')}}</p>
          @endif
  
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col" class="h5">Shopping Bag</th>
                  <th scope="col">Item Name</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Price</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $total=0;
                @endphp
                @foreach($cartItems as $cart)
                <tr>
                  <td>
                    <a href="{{route('delete.cart', $cart->id)}}"><img style="width: 50px" src="{{URL::asset('img/del.png')}}" alt=""></a>

                  </td>
                  <th scope="row">
                    <div class="d-flex align-items-center">
                      <img src="{{URL::asset('uploads/products/'. $cart->image)}}" class="img-fluid rounded-3"
                        style="width: 120px;" alt="Book">
                      <div class="flex-column ms-4">
                        <p>{{$cart->title}}</p>
                      </div>
                    </div>
                  </th>
                  <td class="align-middle">
                    <p class="mb-0" style="font-weight: 500;">{{$cart->category}}</p>
                  </td>
                  <td class="align-middle">
                    <form action="{{route('update.cart')}}" method='POST'>
                      @csrf
                      <div class="d-flex flex-row">
                        <a href='#' class="btn btn-link px-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                        <i class="fas fa-minus"></i>
                      </a>
  
                      <input id="form1" min="0" max={{$cart->pQuantites}} name="quantity" type="number" value="{{$cart->quantites}}"
                        class="form-control form-control-sm" style="width: 50px;" />
  
                      <a href="#" class="btn btn-link px-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                        <i class="fas fa-plus"></i>
                      </a>
                      <input type="hidden" name='id' value="{{$cart->id}}">

                        <div class="form-group">
                          <input type="submit" value="Update" class='btn btn-warning btn-sm'>
                        </div>
                  </div>
                    </form>
                  </td>
                  <td class="align-middle">
                    <p class="mb-0" style="font-weight: 500;">${{$cart->price * $cart->quantites}}</p>
                  </td>
                </tr>
                @php
                    $total+= ($cart->price * $cart->quantites);
                @endphp
                @endforeach
              </tbody>
            </table>
          </div>
  
          <div class="card shadow-2-strong mb-5 mb-lg-0" style="border-radius: 16px;">
            <div class="card-body p-4">
  

  
              <!-- Button to Open the Modal -->
              <button type="button" class="btn btn-primary btn-sm my-2" data-toggle="modal" data-target="#paymodel">
                Payment Via stripe
              </button>

                    <!-- The Modal -->
                    <div class="modal" id="paymodel">
                      <div class="modal-dialog">
                          <div class="modal-content">
                  
                              <!-- Modal Header -->
                              <div class="modal-header">
                              <h4 class="modal-title">Add New Product</h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                      
                              <!-- Modal body -->
                              <div class="modal-body">
                                <form action="{{URL::to('/payment-stripe')}}" method="POST">
                                  @csrf
                                  <div class="row">
                                    <div class="col-lg-12">
                                        <div class="">
                                          <div class="form-outline mb-4 mb-xl-5">
                                            <input type="text" name="name" id="typeName" class="form-control form-control-lg" siez="17"
                                              placeholder="John Smith" />
                                            <label class="form-label" for="typeName">Name on card</label>
                                          </div>
                      
                                          <div class="form-outline mb-4 mb-xl-5">
                                            <input type="email" name="email" id="typeExp" class="form-control form-control-lg" placeholder="E-mail"
                                              size="7" id="exp" minlength="7" required/>
                                            <label class="form-label" for="typeExp">E-mail</label>
                                          </div>
                                        </div>
                                        <div class="">
                                          <div class="form-outline mb-4 mb-xl-5">
                                            <input type="text" name="cell" id="typeText" class="form-control form-control-lg" siez="17"
                                              placeholder="+880123456789" minlength="11" maxlength="11" required/>
                                            <label class="form-label" for="typeText">Cell Number</label>
                                          </div>
                      
                                          <div class="form-outline mb-4 mb-xl-5">
                                            <input type="text" name="address" id="typeText" class="form-control form-control-lg"
                                              placeholder="Address" required/>
                                            <label class="form-label" for="typeText">Address</label>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                      <div class="d-flex justify-content-between" style="font-weight: 500;">
                                        <p class="mb-2">Subtotal</p>
                                        <p class="mb-2">${{$total}}</p>
                                      </div>
                      
                                      <div class="d-flex justify-content-between" style="font-weight: 500;">
                                        <p class="mb-0">Shipping</p>
                                        <p class="mb-0">$60</p>
                                      </div>
                      
                                      <hr class="my-4">
                      
                                      <div class="d-flex justify-content-between mb-4" style="font-weight: 500;">
                                        <p class="mb-2">Total (tax included)</p>
                                        <p class="mb-2">${{$total + 60}}</p>
                                      </div>
                                      <input type="hidden" name='bill' value="{{$total + 60}}">
                  
                  
                                      {{-- sweet alert --}}
                                      {{-- <template id="my-template">
                                        <swal-title>
                                          Which Payment method you want to choose??
                                        </swal-title>
                                        <swal-icon type="warning" color="red"></swal-icon>
                                        <swal-button type="confirm">
                                          <a class='text-light' href="">Stripe</a>
                                        </swal-button>
                                        <swal-button type="cancel">
                                          Cancel
                                        </swal-button>
                                        <swal-button type="deny">
                                          SSL Commerze
                                        </swal-button>
                                        <swal-param name="allowEscapeKey" value="false" />
                                        <swal-param
                                          name="customClass"
                                          value='{ "popup": "my-popup" }' />
                                        <swal-function-param
                                          name="didOpen"
                                          value="popup => console.log(popup)" />
                                      </template>
                                        in button, use this attr data-swal-toast-template="#my-template"
                                      
                                      
                                      
                                       --}}

                                      <button type="submit" class="btn btn-primary btn-block btn-lg" >
                                        <div class="d-flex justify-content-between">
                                          <span>Checkout via Stripe</span>
                                          <span>${{$total + 60}}</span>
                                        </div>
                                      </button>
                      
                                    </div>
                                  </div>
                                </form>
                                <form action="{{route('apply.coupon')}}" method="POST">
                                  @csrf
                                  <div class="form-outline mt-4 mb-4 mb-xl-5">
                                    <input type="text" name="coupon" id="typeText" class="form-control form-control-lg"
                                      placeholder="Apply Coupon" required/>

                                    <input type="submit" class="btn btn-warning btn-block mt-3" value='Apply coupon'>
                                  </div>
                                </form>
                              </div>
                  
                          </div>
                      </div>
                    </div>



                    {{-- ssl payment gateway --}}

              <!-- Button to Open the Modal -->
              <button type="button" class="btn btn-warning btn-sm my-2" data-toggle="modal" data-target="#sslmodel">
                Payment Via Paypal
              </button>

                    <!-- The Modal -->
                    <div class="modal" id="sslmodel">
                      <div class="modal-dialog">
                          <div class="modal-content">
                  
                              <!-- Modal Header -->
                              <div class="modal-header">
                              <h4 class="modal-title">Payment Via Paypal</h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                      
                              <!-- Modal body -->
                              <div class="modal-body">
                                <div class="col-lg-12">
                                  <form action="{{route('apply.coupon')}}" method="POST">
                                    @csrf
                                    <div class="form-outline mb-4 mb-xl-5">
                                      <input type="text" name="coupon" id="typeText" class="form-control form-control-lg"
                                        placeholder="Apply Coupon"/>
  
                                        <input type="submit" class="btn btn-warning btn-block mt-3" value='Apply coupon'>
                                    </div>
                                  </form>
                                  <div class="d-flex justify-content-between" style="font-weight: 500;">
                                    <p class="mb-2">Subtotal</p>
                                    
                                    <p class="mb-2">${{$total}}</p>
                                    
                                  </div>
                  
                                  <div class="d-flex justify-content-between" style="font-weight: 500;">
                                    <p class="mb-0">Shipping</p>
                                    <p class="mb-0">$60</p>
                                  </div>
                  
                                  <hr class="my-4">
                  
                                  <div class="d-flex justify-content-between mb-4" style="font-weight: 500;">
                                    <p class="mb-2">Total (tax included)</p>
                                    <p class="mb-2">${{$total + 60}}</p>
                                  </div>
                                  <form action="{{route('request.paypal')}}" method="POST">
                                  @csrf
                                  <input type="hidden" name='bill' value="{{$total + 60}}">
              
              
                                  {{-- sweet alert --}}
                                  {{-- <template id="my-template">
                                    <swal-title>
                                      Which Payment method you want to choose??
                                    </swal-title>
                                    <swal-icon type="warning" color="red"></swal-icon>
                                    <swal-button type="confirm">
                                      <a class='text-light' href="">Stripe</a>
                                    </swal-button>
                                    <swal-button type="cancel">
                                      Cancel
                                    </swal-button>
                                    <swal-button type="deny">
                                      SSL Commerze
                                    </swal-button>
                                    <swal-param name="allowEscapeKey" value="false" />
                                    <swal-param
                                      name="customClass"
                                      value='{ "popup": "my-popup" }' />
                                    <swal-function-param
                                      name="didOpen"
                                      value="popup => console.log(popup)" />
                                  </template>
                                    in button, use this attr data-swal-toast-template="#my-template"
                                  
                                  
                                  
                                   --}}
                  
                                  <button type='submit' class="btn btn-primary btn-block btn-lg" >
                                    <div class="d-flex justify-content-between">
                                      <span>Checkout via Paypal</span>
                                      <span>${{$total + 60}}</span>
                                    </div>
                                  </button>
                                  </form>
                  
                                </div>
                              </div>
                  
                          </div>
                      </div>
                    </div>












            </div>
          </div>
  
        </div>
      </div>
    </div>
  </section>

  <x-frontfooter />