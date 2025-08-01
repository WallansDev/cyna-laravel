<!-- Header for mobile -->
<header class="site-header d-lg-none">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <button class="mobile-menu-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <i class="fas fa-bars"></i>
            </button>

            <div class="logo-container">
                <img src="{{ asset('images/logo-cyna blanc.png') }}" alt="Cyna Logo" class="logo">
            </div>

            <div class="d-flex header-icons">
                @if (Auth::user())
                    <!-- Dropdown user menu -->
                    <div class="dropdown">
                        <a class="me-3 cart-icon" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('tickets.index') }}">
                                    <i class="fa-solid fa-ticket me-2"></i>Tickets
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="fas fa-user me-2"></i>Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('cart.index') }}">
                                    <i class="fas fa-shopping-cart me-2"></i>Panier
                                </a>
                            </li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa-solid fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}">
                        <i class="fas fa-user"></i>
                    </a>
                @endif  
            </div>
        </div>
    </div>
</header>

<!-- Mobile off-canvas menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu"
    style="background: linear-gradient(to right, rgba(0,0,0,0), var(--dark-bg)),#010033 ! important;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form class="search-form mb-4">
            <input type="text" class="form-control" placeholder="Rechercher...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('home') }}">Accueil</a>
            </li>

             <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('services.index') ? 'active' : '' }}"
                    href="{{ route('services.index') }}">Services</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('categories.index') ? 'active' : '' }}"
                    href="{{ route('categories.index') }}">Catégories</a>
            </li>


            @if (auth()->check() && auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                </li>
            @endif
        </ul>

        <!-- Mobile footer links -->
        <div class="mt-5">
            <h6 class="text-white">Informations</h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('cgu') }}">CGU</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ url('/mentions') }}">Mentions légales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ url('/contact') }}">Contact</a>
                </li>
            </ul>
        </div>
        <div class="mt-5">
            <h6 class="text-white">Service client</h6>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="#">Suivi de commande</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Retours et remboursements</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('faq') }}">FAQ</a></li>
            </ul>
        </div>

        <!-- Social media links for mobile -->
        <div class="mt-4 social-icons">
            <a href="https://www.facebook.com/people/Cyna-coiffure/100089800742148/"><i class="fab fa-facebook"></i></a>
            <a href="https://x.com/SCanceill"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com/cynailsartist/"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com/company/cyna-it/"><i class="fab fa-linkedin"></i></a>
        </div>
    </div>
</div>

<!-- Header for desktop -->
<header class="site-header d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2">
                <div class="logo-container">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo-cyna blanc.png') }}" alt="Cyna Logo" class="logo">
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <form class="search-form">
                    <input type="text" class="form-control" placeholder="Rechercher...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="d-flex justify-content-end header-icons">
                    @if (Auth::user())
                        <a href="{{ route('tickets.index') }}" class="me-3 cart-icon d-lg-none">
                            <i class="fa-solid fa-ticket"></i>
                        </a>
                        <!-- Dropdown user menu -->
                        <div class="dropdown">
                            <a class="me-3 cart-icon" href="#" id="userDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('tickets.index') }}">
                                        <i class="fa-solid fa-ticket me-2"></i>Tickets
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="fas fa-user me-2"></i>Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('cart.index') }}">
                                        <i class="fas fa-shopping-cart me-2"></i>Panier
                                    </a>
                                </li>
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fa-solid fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}">
                            <i class="fas fa-user"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Desktop navigation menu -->
        <div class="row mt-3">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <div class="container-fluid px-0">
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav">

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}"
                                        href="{{ route('services.index') }}">Services</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}"
                                        href="{{ route('categories.index') }}">Catégories</a>
                                </li>


                                @if (auth()->check() && auth()->user()->isAdmin())
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
