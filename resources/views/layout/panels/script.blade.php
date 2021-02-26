    <!-- jQuery -->
    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('assets/dist/js/adminlte.min.js')}}"></script>

    <script src="{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>

    <script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

    <script src="{{asset('assets/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    
    @yield('page-script')

    <script>
    // Read Notification Code
    $(document).on('click', '.MarkRead',function(){
        let uuid = $(this).data('id');
        let ReadButton = $(this);
        let NotificationBox = $(this).parent().siblings('a.col');
        $.ajax({
            type: "POST",
            url: `{{ route('notification.read') }}`,
            data: {
                'uuid':uuid,
                '_token': `{{ csrf_token() }}`
            },
            dataType: "json",
            success: function (response) {
                if(response.status){
                    NotificationBox.attr('class', 'col text-secondary');
                    ReadButton.remove();
                }
            }
        });
    });
        
    </script>