<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PT Adikarya <sup></sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="container-fluid sidebar-header p-3">
        <i class="fa-solid fa-table-columns"></i>
        <a class="sidebar-title">Project Dashboard</a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <div id="projects" class="sidebar-item p-3">
        <i class="fas fa-fw fa-cog"></i>
        <a class = "sidebar-item-title" href="{{route('projects.index')}}">Projects</a>
    </div>

    <div id="logout" class="sidebar-item p-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <a class="sidebar-item-title">Logout</a>
    </div>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="closeModal('logoutModal')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-primary" href="{{ route('login.form') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Logout Modal
        function closeModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.classList.remove('show');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }
    </script>
    

    

    

</ul>