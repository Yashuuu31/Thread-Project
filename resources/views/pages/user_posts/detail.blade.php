@extends('layout.master')

@section('content')

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h1 class="card-title">{{ $post->title ?? '' }}</h1>
            @if (Auth::user()->id == $post->user_id)
                <div class="card-tools">
                    <button type="button" data-target="#editPost" data-toggle="modal" class="btn btn-tool">
                        <i class="fas fa-edit text-success"></i>
                    </button>

                    <button data-destroy="{{ route('user_posts.destroy', $post->id) }}" type="button"
                        class="btn btn-tool destroyPost">
                        <i class="fas fa-trash text-danger"></i>
                    </button>
                </div>
            @endif
        </div>
        <div class="card-body">
            {!! $post->des !!}
        </div>
        @php
            $heartIcon = 'far fa-heart fa-lg';
            $isLiked = '0';
            if ($likes) {
                if ($likes->is_liked == 1) {
                    $isLiked = '1';
                    $heartIcon = 'fa fa-heart fa-lg text-danger';
                }
            }
            $starIcon = 'far fa-star fa-lg';
            $isFav = '0';
            if ($favs) {
                if ($favs->is_fav) {
                    $isFav = '1';
                    $starIcon = 'fa fa-star fa-lg';
                }
            }
        @endphp
        <div class="card-footer">
            <div class="card-tools">
                <button class="btn PostLike" value="{{ $isLiked }}" data-post="{{ $post->id ?? '' }}"><i
                        class="{{ $heartIcon }}"></i></button>
                <span class="LikeCount">{{ count($allLikes) ?? ''}}</span>

                <button class="btn PostComment"><i class="far fa-comment fa-lg"></i></button>
                <span class="CommentCount">{{ count($comments) }}</span>

                <a href="{{ route('user_posts.index') }}" class="float-right mt-1">Home</a>
                <button type="button" data-post="{{ $post->id ?? '' }}" value="{{ $isFav }}"
                    class="btn float-right PostFav">
                    <i class="{{ $starIcon ?? '' }}"></i>
                </button>
            </div>
            <div class="container-fluid overflow-auto" style="display: none">

                <div class="container-fluid CommentDiv">
                    @if (count($comments) != null)
                        @foreach ($comments as $comment)
                            @if ($comment->master_comment == 0)
                                <div class="callout callout-danger CommentBox" id="{{ $comment->id }}"
                                    data-id="{{ $comment->id ?? '' }}">
                                    <h6 class="font-weight-bold">{{ $comment->user->name ?? '' }}</h6>
                                    <div class="row">
                                        <p class="col">{{ $comment->comment }}</p>
                                        <button type="button" data-post="{{ $post->id ?? '' }}"
                                            data-comment="{{ $comment->id }}" class="btn ReplyComment">
                                            <i class="fas fa-reply text-success"></i>
                                        </button>
                                        @if (Auth::user()->id == $comment->user_id || $comment->post->user_id == Auth::user()->id)

                                            <button type="button" data-post="{{ $post->id ?? '' }}"
                                                data-comment="{{ $comment->id ?? '' }}" class="btn DestroyComment">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        @endif
                                    </div>

                                    @foreach ($comments as $comm)
                                        @if ($comm->master_comment != 0 && $comm->master_comment == $comment->id)
                                            <div class="callout callout-danger col-md-8 offset-md-1">
                                                <h6 class="font-weight-bold">{{ $comm->user->name ?? '' }}</h6>
                                                <div class="row">
                                                    <p class="col">{{ $comm->comment }}</p>
                                                    @if (Auth::user()->id == $comment->user_id || $comment->post->user_id == Auth::user()->id)

                                                        <button type="button" data-post="{{ $post->id ?? '' }}"
                                                            data-comment="{{ $comm->id ?? '' }}"
                                                            class="btn DestroyComment">
                                                            <i class="fas fa-trash"></i>
                                                        </button>

                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <form action="{{ route('comment.store') }}" data-master="0" method="POST" class="CommentPost"
                    autocomplete="off">
                    <div class="MasterComment"></div>
                    <div class="input-group mt-4">
                        <input type="hidden" name="post_id" value="{{ $post->id ?? '' }}">
                        <input type="text" name="comment" placeholder="Type Comment..." class="form-control">
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Posts Modal Start -->
    <div class="modal fade" id="editPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('user_posts.update', $post->id) }}" id="editForm" method="POST"> @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" value="{{ $post->title ?? '' }}"
                                placeholder="Post Title">
                        </div>

                        <div class="form-group">
                            <textarea name="des">{{ $post->des ?? '' }}</textarea>
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
@endsection


@section('page-script')
    <script>
        $('#editForm [name=des]').summernote();

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
                            HeartIcon.attr('class', 'fa fa-heart fa-lg text-danger');
                            SetLikes.text(LikeCount + 1);
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
                            HeartIcon.attr('class', 'far fa-heart fa-lg');
                            IsLiked.val('0');
                            SetLikes.text(LikeCount - 1);
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
                        'is_fav': 1,
                        'post_id': PostId,
                        '_token': "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            StarIcon.attr("class", "fa fa-star fa-lg");
                            IsFav.val('1');
                        }
                    }
                });
            }

            if (IsFav.val() == '1') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Post Deleted Your Favorite List.',
                    showConfirmButton: false,
                    timer: 1500
                })
                $.ajax({
                    type: "POST",
                    url: "{{ route('favorite.store') }}",
                    data: {
                        'is_fav': 0,
                        'post_id': PostId,
                        '_token': "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            StarIcon.attr("class", "far fa-star fa-lg");
                            IsFav.val('0');
                        }
                    }
                });


            }



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
                master_id: $(this).data('master'),
            }
            // console.log(CommentItem.master_id);
            
            let CommentTemp = '';
            let ReplayTemp = '';

            
            $.ajax({
                type: "POST",
                url: "{{ route('comment.store') }}",
                data: {
                    'master_id': CommentItem.master_id,
                    'comment': CommentItem.text,
                    'post_id': CommentItem.post_id,
                    '_token': "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        CommentTemp = `<div class="callout callout-danger CommentBox" id="${response.comment.id}">
                                        <h6 class="font-weight-bold">{{ Auth::user()->name }}</h6>
                                        <div class="row">
                                            <p class="col">${CommentItem.text}</p>
                                            <button type="button" data-post="{{ $post->id ?? '' }}"
                                                data-comment="${response.comment.id}" class="btn ReplyComment">
                                                <i class="fas fa-reply text-success"></i>
                                            </button>
                                                <button type="button" data-post="{{ $post->id ?? '' }}"
                                                    data-comment="${response.comment.id}" class="btn DestroyComment">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                        </div>`;

                        ReplayTemp = `<div class="callout callout-danger col-md-8 offset-md-1">
                                            <h6 class="font-weight-bold">{{ Auth::user()->name }}</h6>
                                            <div class="row">
                                                <p class="col">${CommentItem.text}</p>
                                                    <button type="button" data-post="{{ $post->id ?? '' }}"
                                                        data-comment="${response.comment.id}" class="btn DestroyComment">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                            </div>`;

                        if(CommentItem.master_id != 0){
                            CommentDiv.find(`#${CommentItem.master_id}`).append(ReplayTemp)
                        }else{
                            CommentDiv.append(CommentTemp);
                        }
                    }
                }
            });
            $(this).find('input[name=comment]').val('');
            $(this).find('.MasterComment').html('');
            $(this).attr('class', 'CommentPost');
            $(this).data('master', '0');
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

        // Comment Replay Code
        $(document).on('click', '.ReplyComment', function() {
            let Master = {
                text: $(this).siblings('p.col').text(),
                username: $(this).parent().siblings('h6').text(),
                comment_id: $(this).data('comment')
            }
            let CommentForm = {
                FormDiv: $(this).parents('.overflow-auto').find('.CommentPost'),
                MasterComment: $(this).parents('.overflow-auto').find('.CommentPost .MasterComment'),
            }
            console.log(Master);
            let CommentTemp = `
                    <div class="callout callout-danger">
                                            <h6 class="font-weight-bold">${Master.username}</h6>
                                            <div class="row">
                                                <p class="col">${Master.text}</p>
                                                <span class="btn ReplyCancel">
                                                    <i class="fa fa-times"></i>    
                                                </span>
                                            </div>
                    </div>
                    `;
            CommentForm.MasterComment.html(CommentTemp);
            CommentForm.FormDiv.data('master', `${Master.comment_id}`);
            CommentForm.FormDiv.attr('class', 'CommentPost card-body bg-white');
        })

        $(document).on('click', '.ReplyCancel', function() {
            let Master = {
                comment: $(this).parents('.MasterComment'),
            }

            Master.comment.html('');
            Master.comment.parent().data('master', `0`);
            Master.comment.parent().attr('class', 'CommentPost');

        });

    </script>
@endsection
