<x-adminheader title="Products"/>
      <div class="main-panel">
        <div class="content-wrapper">

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    @if(Session::has('success'))

                    <p class='alert alert-success'>{{Session::get('success')}} <button class='close' data-dismissed='alert'>&times;</button> </p>


                    @endif
                    <p class="card-title mb-0">Team Members</p>
                    <!-- Button to Open the Modal -->
                    <button type="button" class="btn btn-primary btn-sm my-2" data-toggle="modal" data-target="#addnewmodal">
                        Add New Team Member
                    </button>

                    <!-- The Modal -->
                    <div class="modal" id="addnewmodal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                    
                                <!-- Modal Header -->
                                <div class="modal-header">
                                <h4 class="modal-title">Add New Product</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                        
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form action="{{route('team.add')}}" method='POST' enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="">Name:</label>
                                            <input type="text" name='name' placeholder='Name' class='form-control'>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Product Image:</label>
                                            <input type="file" name='image' class='form-control'>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Cell:</label>
                                            <textarea type="text" name='cell' placeholder='Cell' class='form-control'></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Role:</label>
                                            <input type="text" name='role' placeholder='Role ex: Designer, developer' class='form-control'>
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" value='Upload Product' class='btn btn-info'>
                                        </div>
                                    </form>
                                </div>
                    
                            </div>
                        </div>
                    </div>

                    <a class='btn btn-info btn-sm' href="{{route('team.trash.page')}}">Trash</a>
                  
                  <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Image</th>
                          <th>Cell</th>
                          <th>Role</th>
                          <th>Action</th>
                        </tr>  
                      </thead>
                      <tbody>

                        <tr>
                            @php
                                $i= 1;    
                            @endphp
                        @foreach($my_team as $team)
                        @php
                            $i++
                        @endphp
                            <td>{{$i}}</td>
                          <td>{{$team->name}}</td>
                          <td><img src="{{URL::asset('uploads/teams/'.$team->image)}}" alt=""></td>
                          <td class="font-weight-bold">{{$team->cell}}</td>
                          <td class="font-weight-medium"><div class="badge badge-warning">{{$team->role}}</div></td>
                          <td>
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updatemodal{{$i}}">
                            Update
                            </button>  

                             <!-- The Modal -->
                    <div class="modal" id="updatemodal{{$i}}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                    
                                <!-- Modal Header -->
                                <div class="modal-header">
                                <h4 class="modal-title">Update Team</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                        
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form action="{{route('update.team', $team->id)}}" method='POST' enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="">Name:</label>
                                            <input type="text" value="{{$team->name}}" name='name' placeholder='Name' class='form-control'>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Product Image:</label>
                                            <input type="file" name='image' class='form-control'>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Role</label>
                                            <textarea type="text" name='role' placeholder='Role ex: Designer, Developer' class='form-control'>{{$team->role}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Cell</label>
                                            <textarea type="text" name='cell' placeholder='Cell' class='form-control'>{{$team->cell}}</textarea>
                                        </div>
 
                                        <div class="form-group">
                                            <input type="submit" value='Update Product' class='btn btn-info'>
                                        </div>
                                    </form>
                                </div>
                    
                            </div>
                        </div>
                    </div>

                            <a href="{{route('trash.team', $team->id)}}" class='btn btn-danger btn-sm'>Trash</a>
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
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->


<x-adminfooter />

