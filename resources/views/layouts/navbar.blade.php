<nav class="navbar">
    <div class="navbar-left">
        {{-- Hamburger: hanya muncul di mobile --}}
        <button class="hamburger-btn" id="sidebarToggle" aria-label="Buka menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>

    <div class="navbar-right">
        <span>{{ auth()->user()->name ?? 'User' }}</span>
    </div>
</nav>