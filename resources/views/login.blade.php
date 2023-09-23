<x-frontheader />


  <!-- login Section Begin -->
  <section class="contact spad">
    <div class="container">
        <div class="section-title">
            <h2 class='text-center text-light'>Sign in For Your Dashboard</h2>
            <p>Sign in now!!!</p>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 mx-auto">
                <div class="msg">
                    @if(Session::has("success"))
                    <p class='alert alert-success'>{{Session::get('success')}}</p>
                    @endif
                    @if(Session::has("error"))
                    <p class='alert alert-danger'>{{Session::get('error')}}</p>
                    @endif
                </div>
                <div class="contact__form">
                    <form action="{{URL::to('/login_user')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="text" name='email' placeholder="E-mail" require>
                            </div>
                            <div class="col-lg-12">
                                <input type="password" name='password' placeholder='Password' require>
                                <button type="submit" class="site-btn">Sign In</button>
                            </div>
                            <a href="{{URL::to('google/login')}}">
                                <img src="{{URL::asset('googlesignin.png')}}" style='height: 120px;' alt="">
                            </a>

                        </div>
                        <p>Don't have any account? Click here for <a href="{{URL::to('/register')}}">Register</a></p>
                    </form>
                </div>
            </div>

        </div>






    </div>
</section>
<!-- login Section End -->



<x-frontfooter />