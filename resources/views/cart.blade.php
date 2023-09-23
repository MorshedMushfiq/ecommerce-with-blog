<x-frontheader />

<div class="container mt-5 mb-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-10">
            <div class="card">
                @if(Session::has("success"))
                <p class='alert alert-success'>{{Session::get('success')}}</p>
                @endif
                @if(Session::has("error"))
                <p class='alert alert-danger'>{{Session::get('error')}}</p>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="images p-3">
                            <div class="text-center p-4"> <img id="main-image" src="{{URL::asset('uploads/products/'. $single_product->image)}}" width="250" /> </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="product p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center"> <i class="fa fa-long-arrow-left"></i> <a class="ml-1 btn btn-danger btn-danger" href="{{route('cake.index')}}">Back</a> </div>
                            </div>
                            <div class="mt-4 mb-3"> <span class="text-uppercase text-muted brand">Cake Town</span>
                                <h5 class="text-uppercase">{{$single_product->title}}</h5>
                                <div class="price d-flex flex-row align-items-center"> <span class="act-price">${{$single_product->price}}</span>
                                </div>
                            </div>
                            <p class="about">{{$single_product->description}}</p>

                        {{-- <form action="{{route('cake.price')}}" method="POST">
                            @csrf
                            <div class="quantity">
                                <input type="number" class='form-control' name='quantity' min='1' value="1" max="{{$single_product->quantites}}">
                            </div>
                            <input type="hidden" name='id' value='{{$single_product->id}}'>
                            <button type='submit' name='add_cart' class="btn btn-danger text-uppercase mr-2 px-4">Add to cart</button>
                        </form> --}}


                        

                        <form action="{{route('add.cart')}}" method='POST'>
                        @csrf
                            

                        <div class="d-flex flex-row mb-3">
                          <a href='#' class="btn btn-link px-2"
                            onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                            <i class="fas fa-minus"></i>
                          </a>


                            <input id="form1" name='quantity' min="1" max="{{$single_product->quantity}}" name="quantity" value="1" type="number" value="{{$single_product->quantity}}"
                            class="form-control form-control-sm" style="width: 50px;" />
      
                          <a href="#" class="btn btn-link px-2"
                            onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                            <i class="fas fa-plus"></i>
                          </a>
                        </div>

                        <input type="hidden" name='id' value="{{$single_product->id}}">

                        <input type='submit' name='add_cart' class="btn btn-danger text-uppercase mr-2 px-4" value="Add to Cart">


                            

                        </div>    
                        
                      </form>
                         

                        



                </div>
            </div>
        </div>
    </div>
</div>






<x-frontfooter />