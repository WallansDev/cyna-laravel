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
                <a href="#" class="me-3">
                    <i class="fas fa-th-large"></i>
                </a>
                <a href="#">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Mobile off-canvas menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" style="background: linear-gradient(to right, rgba(0,0,0,0), var(--dark-bg)),#010033 ! important;">
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
                <a class="nav-link text-white" href="#">Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Catégories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Nouveautés</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Promotions</a>
            </li>
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
                        <a href="{{ route('tickets.index') }}" class="me-3 cart-icon">
                            <i class="fa-solid fa-ticket"></i>
                        </a>

                        <a class="me-3 cart-icon" href="{{ route('profile.index') }}">
                            <i class="fas fa-user"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <a id="logout-button" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </form>
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

                                <div class="dropdown">
                                    <a class="btn dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Page d'accueil
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('carousel.index') }}">Voir
                                                carousel</a></li>
                                        <li><a class="dropdown-item" href="{{ route('services.topProducts') }}">Ordre
                                                Services Top</a></li>
                                        <li> <a class="dropdown-item"
                                                href="{{ route('categories.orderIndex') }}">Ordre
                                                catégories</a></li>
                                    </ul>
                                </div>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}"
                                        href="{{ route('services.index') }}">Services</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                                        href="{{ route('categories.viewAdmin') }}">Catégories Admin</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                                        href="{{ route('categories.index') }}">Catégories</a>
                                </li>

                                @if (auth()->check() && auth()->user()->isAdmin())
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                            href="{{ route('users.index') }}">Utilisateurs</a>
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
