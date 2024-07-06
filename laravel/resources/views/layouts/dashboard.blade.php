<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,
                   initial-scale=1.0">
    <title>Bootstrap5 Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/0c265f6a3f.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>

<body>
    {{-- Side Bar --}}
    <div class="container-fluid p-0 d-flex h-100">
        <div id="bdSidebar" 
             class="d-flex flex-column 
                    flex-shrink-0 
                    p-3 bg-success
                    text-white offcanvas-md offcanvas-start">
            <a href="#" 
               class="navbar-brand">
            </a><hr>
            <ul class="mynav nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-1">
                    <a href="#">
                        <i class="fa-regular fa-user"></i>
                        Project
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="#">
                        <i class="fa-regular fa-bookmark"></i>
                        Add Project
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="#">
                        <i id="logoutIcon" class="fa-solid fa-arrow-right-from-bracket"></i>
                        Logout
                    </a>
                </li>
                
                
            </ul>
            <hr>
        </div>

        <div class="bg-light flex-fill">
            <div class="p-2 d-md-none d-flex text-white bg-success">
                <a href="#" class="text-white" 
                   data-bs-toggle="offcanvas"
                   data-bs-target="#bdSidebar">
                    <i class="fa-solid fa-bars"></i>
                </a>
                <span class="ms-3">GFG Portal</span>
            </div>
            
                {{-- Page Content --}}
                @include('components.project-list')
            </div>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#logoutIcon').on('click', function() {
                $.ajax({
                    url: '{{ route("logout") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    success: function(response) {

                        window.location.href = '{{ route("login.form") }}'; 
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
    

</body>

</html>