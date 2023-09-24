<div id="comment_section">
    
    <x-frontheader />


<div class="container mt-5">
    <div class="container">
        <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
            <h2 class="text-primary font-secondary">Our Blogs</h2>
            <h1 class="display-4 text-uppercase">CakeZone Posts</h1>
        </div>
    </div>


    <div class="d-flex justify-content-between row my-2">
        <div class="col-md-12 border border-info">
            <div class="d-flex flex-column comment-section">
                <div class="bg-white p-2">
                    @foreach ($user_infos->unique() as $user)
                    @if($single_post->user_id==$user->id)
                    <div class="d-flex flex-row user-info"><img class="rounded-circle" src="{{URL::asset('uploads/profiles/'. $user->image)}}" width="40">
                        <div class="d-flex flex-column justify-content-start ml-2"><span class="d-block font-weight-bold name">{{$user->name}}</span><span class="date text-black-50">Shared publicly - {{$single_post->created_at}}</span></div>
                        @endif
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <p class='display-4'>{{$single_post->title}}</p>
                        <p class="comment-text">{{$single_post->post_description}}</p>
                        <img class="img-fluid" src="{{URL::asset('uploads/posts/'.$single_post->post_image)}}">
                    </div>
                </div>
                <h2 class='text-success display-5'>All Comments</h2>
                @foreach($show_comments as $all_comments)
                @if($all_comments->post_id==$single_post->id)
                <div class="comment">
                    
                    <div class="d-flex flex-row justify-content-start align-items-start py-2">
                        @foreach ($user_infos->unique() as $user)
                        @if($all_comments->user_id==$user->id)
                        
                        <img class="rounded-circle" src="{{URL::asset('uploads/profiles/'.$user->image )}}" width="40">
                        
                        <div class="second py-2 px-2 mb-1"> <span class="text1">
                            <div class="d-flex flex-column justify-content-start ml-2 mb-3"><span class="d-block font-weight-bold name">{{$user->name}}</span><span class="date text-black-50">Shared publicly - {{$all_comments->created_at}}</span></div>
                            @endif
                            @endforeach
                            {{$all_comments->comment}}
                            
                        </span>
                        </div>
                    </div>
                    @if($user->type=="Admin" &&$user->id==$all_comments->user_id)
                    <a class='text-underline text-danger' href="{{route('delete.comment', $all_comments->id)}}">Delete comment</a>
                    @endif
                </div>

                @endif
                @endforeach 
                <div class="bg-light p-2">
                    @auth
                    <div class="d-flex flex-row align-items-start">
                        <img class="rounded-circle" src="{{URL::asset('uploads/profiles/'. Auth::user()->image)}}" width="40">
                        <form style="width: 100%;" action="{{url('post_comment')}}" method="POST" id="commentForm">
                            @csrf
                            <textarea name='comment' class="form-control ml-1 shadow-none textarea"></textarea>
                    </div>

                            <input type="hidden" name="post_id" value="{{$single_post->id}}">
                            <div class="mt-2 text-right"><button class="btn btn-primary btn-sm shadow-none" type="submit">Post comment</button><button class="btn btn-outline-primary btn-sm ml-1 shadow-none" type="reset">Cancel</button>
                            </div>
                        </form>
                        
                </div>        
                @else

                <p class='mt-3 p-2'>Please <a class="fw-bold" href="{{route('cake.login')}}">Login</a> or <a href="{{route('cake.register')}}" class="fw-bold">Register</a> For Leaving a comment here.</p>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>
{{-- posts section ends here --}}

<x-frontfooter />

</div>