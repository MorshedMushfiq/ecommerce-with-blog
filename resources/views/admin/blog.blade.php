<x-adminheader/>
              {{-- orders --}}

              <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">

                        @if(Session::has("success"))
                        <p class='alert alert-success my-2'>{{Session::get("success")}} <button class='close' data-dismiss="alert">&times;</button> </p>
    
                        @endif
                        <p class="card-title mb-0">My Blogs</p>
                        <!-- Button to Open the Modal -->
                        <button type="button" class="btn btn-primary btn-sm my-2" data-toggle="modal" data-target="#addnewmodal">
                            Add New Post
                        </button>
    
                        <!-- The Modal -->
                        <div class="modal" id="addnewmodal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                        
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                    <h4 class="modal-title">Add New Post</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                            
                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <form action="{{route('blog.upload')}}" method='POST' enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="">Post Title:</label>
                                                <input type="text" value="" name='post_title' placeholder='Post Title' class='form-control'>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Post Image:</label>
                                                <input type="file" name='post_image' class='form-control'>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Post Description:</label>
                                                <textarea type="text" name='post_description' placeholder='Post Description' class='form-control'></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Post tags:</label>
                                                <textarea type="text" name='post_tags' placeholder='post Tags' class='form-control'></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Post Comment:</label>
                                                <input type="text" value=""  name='post_comment' placeholder='Post Comment' class='form-control'>
                                            </div>
    
                                            <div class="form-group">
                                                <input type="submit" value='Upload Post' class='btn btn-info'>
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
                              <th>Post Title</th>
                              <th>description</th>
                              <th>Image</th>
                              <th>Comments</th>
                              <th>Post Tags</th>
                              <th>Post Status</th>
                              <th>Action</th>
                            </tr>  
                          </thead>
                          <tbody>
                            @php
                                $i=0;
                            @endphp

                            @foreach($blog as $post)
                            @if(Auth::user()->id==$post->user_id)

                            @php
                                $i++;
                            @endphp
                            <tr>
                              <td>1</td>
                              <td>{{$post->title}}</td>
                              <td style='width: 100%;'><p>{{$post->post_description}}</p></td>
                              <td><img src="{{URL::asset('/storage/uploads/posts/'. $post->post_image)}}" alt=""></td>
                              <td>{{$post->post_comment}}</td>
                              <td class="font-weight-bold">{{$post->post_tags}}</td>
                              <td>{{$post->created_at}}</td>
                              <td>
                                <button type="button" class="btn btn-primary btn-sm my-2" data-toggle="modal" data-target="#addnewmodal{{$i}}">
                                    Edit your Post
                                </button>
            
                                <!-- The Modal -->
                                <div class="modal" id="addnewmodal{{$i}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                            <h4 class="modal-title">Edit Post</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                    
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="{{route('update.post', $post->id)}}" method='POST' enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="">Post Title:</label>
                                                        <input type="text" value="{{$post->title}}" name='post_title' placeholder='Post Title' class='form-control'>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Post Image:</label>
                                                        <input type="file" name='post_image' class='form-control'>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Post Description:</label>
                                                        <textarea type="text" name='post_description' placeholder='Post Description' class='form-control'>{{$post->post_description}}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Post tags:</label>
                                                        <textarea type="text" name='post_tags' placeholder='post Tags' class='form-control'>{{$post->post_tags}}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Post Comment:</label>
                                                        <input type="text" value="{{$post->post_comment}}"  name='post_comment' placeholder='Post Comment' class='form-control'>
                                                    </div>
            
                                                    <div class="form-group">
                                                        <input type="submit" value='Update Post' class='btn btn-primary'>
                                                    </div>
                                                </form>
                                            </div>
                                
                                        </div>
                                    </div>
                                </div>
                                <a href="{{route('delete.post', $post->id)}}" class='btn btn-danger btn-sm'>Delete</a>
                                
                              </td>    
                            </tr>
                            @endif
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            
<x-adminfooter />



