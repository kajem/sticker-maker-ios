<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/dashboard')}}" class="brand-link">
        <img src="/images/logo.png" alt="Sticker Maker Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">StickerMaker</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset("/bower_components/admin-lte/dist/img/user2-160x160.png") }}"
                     class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
            <a href="/user/profile" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!--Dashboard Menu-->
                <li class="nav-item">
                    <a href="{{url('/dashboard')}}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}"">
                    <i class="fas fa-th"></i>
                    <p>Dashboard</p>
                    </a>
                </li>
                <!--User Maneu-->
                @if( auth()->user()->id == 1 )
                <li class="nav-item has-treeview {{ Request::is('user/*') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link">
                        <i class="fas fa-users"></i>
                        <p>
                            Users
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{url('user/list')}}"
                               class="nav-link {{ Request::is('user/list') ? 'active' : '' }}">
                                <i class="fas fa-list"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('/user/add')}}"
                               class="nav-link {{ Request::is('user/add') ? 'active' : '' }}">
                                <i class="fas fa-plus"></i>
                                <p>Add New</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                <!--Category Menu-->
                <li class="nav-item has-treeview {{ Request::is('category/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="fas fa-object-group"></i>
                        <p>
                            Category
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{url('/category/list')}}"
                               class="nav-link {{ Request::is('category/list') ? 'active' : '' }}">
                                <i class="fas fa-list"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('/category/list/sort2')}}"
                               class="nav-link {{ Request::is('category/list/sort2') ? 'active' : '' }}">
                                <i class="fas fa-list"></i>
                                <p>Sort 2</p>
                            </a>
                        </li>
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{url('/category/22')}}"--}}
{{--                               class="nav-link {{ Request::is('category/22') ? 'active' : '' }}">--}}
{{--                                <i class="far fa-smile-wink"></i>--}}
{{--                                <p>Emoji</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
                        <li class="nav-item">
                            <a href="{{url('/category/add')}}"
                               class="nav-link {{ Request::is('category/add') ? 'active' : '' }}">
                                <i class="fas fa-plus"></i>
                                <p>Add New</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--Item Menu-->
                <li class="nav-item has-treeview {{ Request::is('item/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="far fa-grimace"></i>
                        <p>
                            Sticker Package
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{url('item/list')}}"
                               class="nav-link {{ Request::is('item/list') ? 'active' : '' }}">
                                <i class="fas fa-list"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('/item/add')}}"
                               class="nav-link {{ Request::is('item/add') ? 'active' : '' }}">
                                <i class="fas fa-plus"></i>
                                <p>Add New</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('/item/search-keyword/list')}}"
                               class="nav-link {{ Request::is('item/search-keyword/list') ? 'active' : '' }}">
                                <i class="fab fa-searchengin"></i>
                                <p>Search Keywords</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('/item/website-home-sticker-packages')}}"
                               class="nav-link {{ Request::is('item/website-home-sticker-packages') ? 'active' : '' }}">
                               <i class="fas fa-surprise"></i>
                                <p>Website home packages</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--Post Menu-->
                <li class="nav-item has-treeview {{ Request::is('post/how-to-use-sm/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="fas fa-blog"></i>
                        <p>
                            How to use SM
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{url('post/how-to-use-sm/list')}}"
                               class="nav-link {{ Request::is('post/how-to-use-sm/list') ? 'active' : '' }}">
                                <i class="fas fa-list"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('/post/how-to-use-sm/add')}}"
                               class="nav-link {{ Request::is('post/how-to-use-sm/add') ? 'active' : '' }}">
                                <i class="fas fa-plus"></i>
                                <p>Add New</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--Static Value Menu-->
                @if( auth()->user()->id == 1 )
                <li class="nav-item">
                    <a href="{{url('/static-value/list')}}" class="nav-link {{ Request::is('static-value/list') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        <p>Static Values</p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="/user/profile" class="nav-link {{ Request::is('user/profile') ? 'active' : '' }}">
                       <i class="fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
