@extends('layout.master')
@section('title')
    {{ $moduleName ?? '' }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mx-2">
                <h3 class="col">{{ $moduleName ?? '' }}</h3>
                <button class="btn btn-primary addPost" data-toggle="modal" data-target="#addPost">Add Post</button>
            </div>
            <hr>

            <!-- Add Posts Modal Start -->
            <div class="modal fade" id="addPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('user_posts.store') }}" id="addForm" method="POST"> @csrf 
                            <input type="hidden" name="_method" value="">
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
                                <button type="submit" name="_submit" class="btn btn-primary">Uplode Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Add Posts Modal End -->

            <!-- Edit Posts Modal Start -->
            {{-- <div class="modal fade" id="editPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <form action="" id="editForm" method="POST"> @csrf @method('PUT')
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
            </div> --}}
            <!-- Edit Posts Modal End -->


            <div class="row justify-content-center">
                @foreach ($UserPosts as $item)
                    <div class="col-md-3 mx-md-3">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h4 class="card-title">{{ $item->title ?? '' }}</h4>
                                @if (Auth::user()->id == $item->user_id)
                                    <div class="card-tools">
                                        <button type="button" data-target="#addPost" data-toggle="modal"
                                            class="btn btn-tool editPost"
                                            data-edit="{{ route('user_posts.edit', $item->id) }}"
                                            data-update="{{ route('user_posts.update', $item->id) }}">
                                            <i class="fas fa-edit text-success"></i>
                                        </button>

                                        <button type="button" class="btn btn-tool destroyPost"
                                            data-destroy="{{ route('user_posts.destroy', $item->id) }}">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body overflow-hidden" style="height: 160px;">
                                {!! $item->des ?? '' !!}
                            </div>
                            <!-- Comment & Like Find Code -->
                            <div class="card-footer">
                                <div class="card-tools">
                                    <button class="btn PostLike" value="{{ count($item->isLiked) != 0 ? '1' : '0' }}"
                                        data-post="{{ $item->id ?? '' }}"><i class="{{ count($item->isLiked) != 0 ? 'fa fa-heart fa-lg text-danger' : 'far fa-heart fa-lg' }}"></i></button>
                                    <span class="LikeCount">{{ count($item->allLikes)}}</span>
                                    <a href="{{ route('user_posts.show', $item->id)}}" class="float-right mt-1">Read More</a>

                                    <button type="button" data-post="{{ $item->id ?? ''}}" value="{{ count($item->isFav) != 0 ? '1' : '0' }}" class="btn float-right PostFav">
                                        <i class="{{ count($item->isFav) != 0 ? 'fa fa-star fa-lg' : 'far fa-star fa-lg' }}"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@section('page-script')
    <script>
        $('#addForm [name=des]').summernote();

        // Succee Msg Code ---
        if (`{{ Session::get('success') }}`) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `{{ Session::get('success') }}`,
                showConfirmButton: false,
                timer: 1500
            })
        }

        // Add Post Code ---
        $(document).on('click','.addPost',function(){
            $('#exampleModalLabel').text('Add Form');
            $('#addForm').attr('action', `{{ route('user_posts.store')}}`);
            $('#addForm [name=_submit]').text('Upload Post');
            $('#addForm [name=_method]').val('');
            $('#addForm [name=title]').val('');
            $('#addForm [name=des]').summernote('code', '');
            $('#addForm [name=des]').html('');
        });

        // Edit Pot Get Post Data
        $('.editPost').on('click', function() {
            let editUrl = $(this).data('edit');
            let updateUrl = $(this).data('update');
            // console.log(updateUrl);
            $('#exampleModalLabel').text('Edit Form');
            $('#addForm [name=_submit]').text('Update Post');
            $('#addForm').attr('action', updateUrl);
            $('#addForm [name=_method]').val('PUT');
            $.ajax({
                type: "GET",
                url: editUrl,
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $("#addForm [name=title]").val(response.data.title);
                        $("#addForm [name=des]").html(response.data.des);
                        $('#addForm [name=des]').summernote('code', response.data.des);
                    }
                }
            });
        });

        // Read More Code ---
        $(document).on('click', '.ReadMore', function() {
            let CardBody = $(this).parent().parent().parent().find('.card-body');
            if (CardBody.attr('class') == "card-body overflow-hidden") {
                CardBody.removeClass('overflow-hidden');
                CardBody.addClass('overflow-auto');
            } else {
                CardBody.removeClass('overflow-auto');
                CardBody.addClass('overflow-hidden');
            }
        });

        // Destroy Post Code ---
        $(document).on('click', '.destroyPost', function() {
            let destroyUrl = $(this).data('destroy')
            Swal.fire({
                title: 'Are you sure Delete This Post ?',
                // text: "Delete",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: destroyUrl,
                        data: {
                            '_token': "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                location.replace(`{{ route('user_posts.index') }}`);
                            }
                        }
                    });
                }
            })
        });

        // Comment Box Extend Code ---
        $(document).on('click', '.PostComment', function() {
            let CommentBox = $(this).parent().siblings('.container-fluid');
            CommentBox.slideToggle();
        });

        // Comment Post Code ---
        $(document).on('submit', '.CommentPost', function(e) {
            e.preventDefault();
            let CommentDiv = $(this).siblings('.CommentDiv');
            let CommentItem = {
                text: $(this).find('input[name=comment]').val(),
                post_id: $(this).find('input[name=post_id]').val(),
            }
            let CommentTemp = '';
            $.ajax({
                type: "POST",
                url: "{{ route('comment.store') }}",
                data: {
                    'comment': CommentItem.text,
                    'post_id': CommentItem.post_id,
                    '_token': "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    if(response.status){
                        CommentTemp = `<div class="callout callout-danger">
                                    <h6 class="font-weight-bold">{{ Auth::user()->name }}</h6>
                                    <div class="row">
                                        <p class="col">${CommentItem.text}</p>
                                            <button type="button" data-post="{{ $item->id ?? '' }}"
                                                data-comment="${response.comment.id}" class="btn DestroyComment">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                    </div>`;
                        CommentDiv.append(CommentTemp);
                    }
                }
            });
            $(this).find('input[name=comment]').val('');
        });

        // Destroy Comment Code ---
        $(document).on('click', '.DestroyComment', function() {
            let comment_id = $(this).data('comment');
            let post_id = $(this).data('post');
            let CommentTex = $(this).parent().parent();
            let CommentCount = parseInt($(this).parent().parent().parent().siblings('.card-tools').find(
                '.CommentCount').text());
            let SetCommentCount = $(this).parent().parent().parent().siblings('.card-tools').find('.CommentCount');

            Swal.fire({
                title: 'Are you sure delete this comment ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('comment.destroy') }}",
                        data: {
                            'post_id': post_id,
                            'comment_id': comment_id,
                            '_token': "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                CommentTex.remove();
                                SetCommentCount.text(CommentCount - 1);
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Comment deleted successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        }
                    });

                }
            })

        })

        // Like Event Code ---
        $(document).on('click', '.PostLike', function() {
            let PostId = $(this).data('post');
            let IsLiked = $(this);
            let LikeCount = parseInt($(this).siblings('.LikeCount').text());
            let SetLikes = $(this).siblings('.LikeCount');

            let HeartIcon = $(this).find('i');

            console.log(IsLiked.val());
            if (IsLiked.val() == '0') {

                Swal.fire({
                    title: 'Post Liked.',
                    imageUrl: 'https://icons-for-free.com/iconfiles/png/512/instagram+like+notification+icon-1320184017391732020.png',
                    imageWidth: 300,
                    imageHeight: 300,
                    animation: true,
                    showConfirmButton: false,
                    timer: 1000
                })
                $.ajax({
                    type: "POST",
                    url: "{{ route('like.store') }}",
                    data: {
                        'is_liked': 1,
                        'post_id': PostId,
                        'user_id': "{{ Auth::user()->id }}",
                        '_token': "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            SetLikes.text(response.allLikes);
                            HeartIcon.attr('class', 'fa fa-heart fa-lg text-danger');
                            IsLiked.val('1');
                        }
                    }
                });
            }

            if (IsLiked.val() == '1') {
                Swal.fire({
                    title: 'Post Disliked.',
                    imageUrl: 'https://cdn2.iconfinder.com/data/icons/instagram-ui/48/jee-68-512.png',
                    imageWidth: 300,
                    imageHeight: 300,
                    animation: false,
                    showConfirmButton: false,
                    timer: 1000
                })
                $.ajax({
                    type: "POST",
                    url: "{{ route('like.store') }}",
                    data: {
                        'is_liked': 0,
                        'post_id': PostId,
                        'user_id': "{{ Auth::user()->id }}",
                        '_token': "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            SetLikes.text(response.allLikes);
                            HeartIcon.attr('class', 'far fa-heart fa-lg');
                            IsLiked.val('0');
                        }
                    }
                });


            }
        });

        // Post Favorite Code ---
        $(document).on('click', '.PostFav', function() {
            let IsFav = $(this);
            let PostId = $(this).data('post');
            let StarIcon = $(this).find('i');

            if (IsFav.val() == '0') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Post Added Your Favorite List.',
                    showConfirmButton: false,
                    timer: 1500
                })
                $.ajax({
                    type: "POST",
                    url: "{{ route('favorite.store') }}",
                    data: {
                        'is_fav':1,
                        'post_id': PostId,
                        '_token': "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.status){
                            StarIcon.attr("class", "fa fa-star fa-lg");
                            IsFav.val('1');
                        }
                    }
                });
            } 
            
            if(IsFav.val() == '1'){
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Post Removed Your Favorite List.',
                    showConfirmButton: false,
                    timer: 1500
                })
                $.ajax({
                    type: "POST",
                    url: "{{ route('favorite.store') }}",
                    data: {
                        'is_fav':0,
                        'post_id': PostId,
                        '_token': "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.status){
                            StarIcon.attr("class", "far fa-star fa-lg");
                            IsFav.val('0');
                        }
                    }
                });

                
            }



        });

        

    </script>
@endsection
