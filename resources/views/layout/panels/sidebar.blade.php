        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashbord.index') }}" class="nav-link {{ Request::segment(1) == 'dashbord' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>Dashbord</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('user_posts.index') }}" class="nav-link {{ Request::segment(1) == 'user_posts' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-plus-square"></i>
                                <p>User Posts</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
