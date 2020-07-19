<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/dashboard')}}" class="brand-link">
      <img src="{{ asset("/bower_components/admin-lte/dist/img/AdminLTELogo.png") }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">StickerMaker</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset("/bower_components/admin-lte/dist/img/user2-160x160.png") }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{url('/dashboard')}}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}"">
              <i class="fas fa-th"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ Request::is('/category/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link ">
                <i class="fas fa-object-group"></i>
              <p>
                Category
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/category/list')}}" class="nav-link {{ Request::is('/category/list') ? 'active' : '' }}">
                  <i class="fas fa-list"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{url('/static-value/list')}}" class="nav-link {{ Request::is('static-value/list') ? 'active' : '' }}"">
              <i class="fas fa-cogs"></i>
              <p>
                Static Values
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ Request::is('/report/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link ">
                <i class="fas fa-table"></i>
              <p>
                Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/report/search-keyword')}}" class="nav-link {{ Request::is('report/search-keyword') ? 'active' : '' }}">
                  <i class="fab fa-searchengin"></i>
                  <p>Search Keyword</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>