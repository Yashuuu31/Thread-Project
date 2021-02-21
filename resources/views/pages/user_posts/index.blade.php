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
            </div>
            <!-- Edit Posts Modal End -->


            <div class="row justify-content-center">
                @foreach ($UserPosts as $item)
                    <div class="col-md-3 mx-md-3">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h4 class="card-title">{{ $item->title ?? '' }}</h4>
                                @if (Auth::user()->id == $item->user_id)
                                    <div class="card-tools">
                                        <button type="button" data-target="#editPost" data-toggle="modal"
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
                            @php
                                $comments = App\Models\Comment::where('post_id', $item->id)->get();
                            @endphp
                            <div class="card-footer">
                                <div class="card-tools">
                                    <button class="btn"><i class="far fa-heart fa-lg"></i></button>
                                    <button class="btn PostComment"><i class="far fa-comment fa-lg"></i></button>
                                    <button type="button" class="btn float-right ReadMore" data-card-widget="maximize">
                                        <i class="fas fa-expand fa-lg"></i>
                                    </button>
                                </div>
                                <div class="container-fluid" style="display: none;">

                                    @if (count($comments) != null)
                                        @foreach ($comments as $comment)
                                            <div class="callout callout-info">
                                                <h6 class="font-weight-bold">{{ Auth::user()->name ?? '' }}</h6>
                                                <div class="row">
                                                    <p class="col">{{ $comment->comment }}</p>
                                                    @if (Auth::user()->id == $comment->user_id || $comment->post->user_id == Auth::user()->id)
                                                        <button type="button" class="btn">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>`
                                        @endforeach
                                    @endif

                                    <form action="" method="POST" class="CommentPost" autocomplete="off">
                                        <div class="input-group mt-4">
                                            <input type="hidden" name="post_id" value="{{ $item->id ?? '' }}">
                                            <input type="text" name="comment" placeholder="Type Comment..."
                                                class="form-control">
                                            <span class="input-group-append">
                                                <button type="submit" class="btn btn-primary">Send</button>
                                            </span>
                                        </div>
                                    </form>
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
        $('#editForm [name=des]').summernote();

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
            let updateUrl = $(this).data('update');
            // console.log(updateUrl);
            $('#editForm').attr('action', updateUrl);
            $.ajax({
                type: "GET",
                url: editUrl,
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $("#editForm [name=title]").val(response.data.title);
                        $("#editForm [name=des]").html(response.data.des);
                        $('#editForm [name=des]').summernote('code', response.data.des);
                    }
                }
            });
        })

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

        $(document).on('click', '.PostComment', function() {
            let CommentBox = $(this).parent().siblings('.container-fluid');
            CommentBox.slideToggle();
        })

        $(document).on('submit', '.CommentPost', function(e) {
            e.preventDefault();
            let CommentText = $(this).find('input[name=comment]');
            let PostId = $(this).find('input[name=post_id]').val();
            let CommentBox = $(this).parent();
            let LastComment = $(this).parent().find('.callout:last');

            let CommentTemp = `<div class="callout callout-info">
                                                    <h6 class="font-weight-bold">{{ Auth::user()->name ?? '' }}</h6>
                                                    <div class="row">
                                                        <p class="col">${CommentText.val()}</p>
                                                        <button type="button" class="btn">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>`;

            $.ajax({
                type: "POST",
                url: "{{ route('comment.store') }}",
                data: {
                    post_id: PostId,
                    comment: CommentText.val(),
                    '_token': "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        if (CommentBox.find('.callout').html() != null) {
                            LastComment.after(CommentTemp);
                        } else {
                            CommentBox.prepend(CommentTemp);
                        }
                        CommentText.val('');
                    }
                }
            });
            // $(this).find('input[name=comment]').val('')
        })

    </script>
@endsection
