<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
        <img src=<?php echo site_url("assets/dist/img/AdminLTELogo.png"); ?> alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light">Fournisseur</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <br>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            
          <li class="nav-item">
              <a href="<?php echo site_url("Departement/demande"); ?>" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>Demande</p>
              </a>
            </li>
            
          </ul>
        </nav>
      </div>
    </aside>