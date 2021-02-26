<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown show">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                <i class="far fa-bell"></i>
                @if (count(Auth::user()->unreadNotifications) != 0)
                    <span class="badge badge-warning navbar-badge">{{count(Auth::user()->unreadNotifications)}}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right  ">

                @foreach (Auth::user()->Notifications as $item)
                <div class="card-body py-2 row NotificationBox ">
                    <a href="{{ route('user_posts.show', $item->data['data']['post_id'])}}" class="col {{ $item->read_at==null ? 'text-dark' : 'text-secondary' }} ">
                        <h6 class="font-weight-bold">{{ $item->data['data']['user']}}</h6>
                        <p class="text-sm">{{ $item->data['data']['message']}}</p>
                    </a>
                    @if ($item->read_at == null)    
                        <div class="row">
                            <button data-id="{{ $item->id }}" class="btn MarkRead">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="dropdown-divider"></div>
                @endforeach
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>
