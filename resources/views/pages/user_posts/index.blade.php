@extends('layout.master')
@section('title')
    {{ $moduleName ?? '' }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mx-2">
                <h3 class="col">{{ $moduleName ?? '' }}</h3>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addPost">Add Post</button>
            </div>
            <hr>

            <!-- Add Posts Modal Start -->
            <div class="modal fade" id="addPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('user_posts.store') }}" id="addForm" method="POST"> @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add Post</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="text" name="title" class="form-control" placeholder="Post Title">
                                </div>

                                <div class="form-group">
                                    <textarea name="des"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Uplode Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Add Posts Modal End -->

            <!-- Edit Posts Modal Start -->
            <div class="modal fade" id="editPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('user_posts.store') }}" id="editForm" method="POST"> @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="text" name="title" class="form-control" placeholder="Post Title">
                                </div>

                                <div class="form-group">
                                    <textarea name="des"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Uplode Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Edit Posts Modal End -->


            <div class="row justify-content-center">
                @foreach ($UserPosts as $item)
                    <div class="card card-outline card-primary col-md-3 mx-4">
                        <div class="card-header">
                            <h3 class="card-title">{{ $item->title ?? '' }}</h3>

                            <div class="card-tools">
                                <button type="button" data-target="#editPost" data-toggle="modal"
                                    class="btn btn-tool editPost" data-edit="{{ route('user_posts.edit', $item->id) }}"
                                    data-update="{{ route('user_posts.update', $item->id) }}">
                                    <i class="fas fa-edit text-success"></i>
                                </button>

                                <button type="button" class="btn btn-tool">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            {!! $item->des ?? '' !!}
                        </div>
                        <!-- /.card-body -->
                    </div>
                @endforeach



            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $('#addForm [name=des]').summernote();
        $('#editForm [name=des]').summernote();
        $('#addForm [name=des]').html("123");

        if (`{{ Session::get('success') }}`) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `{{ Session::get('success') }}`,
                showConfirmButton: false,
                timer: 1500
            })
        }


        $('.editPost').on('click', function() {
            let editUrl = $(this).data('edit');

            $.ajax({
                type: "GET",
                url: editUrl,
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $("#editForm [name=title]").val(response.data.title);
                        $("#editForm [name=des]").html(response.data.des);
                        $("#editForm [name=des]").trigger();
                    }
                }
            });
        })

    </script>
@endsection
