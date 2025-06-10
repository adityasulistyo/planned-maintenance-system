<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo & System Title -->
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden sm:flex items-center space-x-6">
                <!-- Dashboard -->
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>

                <!-- Inventaris Dropdown -->
                <div class="relative group">
                    <button type="button"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 focus:outline-none">
                        Inventaris
                        <svg class="ml-1 h-4 w-4 fill-current" viewBox="0 0 20 20">
                            <path d="M5.5 7l4.5 4 4.5-4z" />
                        </svg>
                    </button>
                    <div class="absolute hidden group-hover:block bg-white shadow-md rounded-md mt-1 z-50 w-48 border border-gray-200">
                        <a href="{{ route('spareparts.index', 'ae') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Inventaris AE</a>
                        <a href="{{ route('spareparts.index', 'me') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Inventaris ME</a>
                        <a href="{{ route('spareparts.index', 'pe') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Inventaris PE</a>
                    </div>
                </div>

                <!-- Request Link -->
                <a href="{{ route('request.list') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                    Lihat Daftar Request
                </a>

                <a href="{{ route('running_hours.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">Running Hours</a>

                <a class="nav-link {{ Request::routeIs('reports.*') ? 'active bg-primary' : '' }}" href="{{ route('reports.index') }}">Report</a>
            </div>

            <!-- User Settings -->
            <div class="hidden sm:flex sm:items-center sm:ml-auto">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu -->
            <div class="sm:hidden flex items-center">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('spareparts.index', 'ae')">
                {{ __('Inventaris AE') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('spareparts.index', 'me')">
                {{ __('Inventaris ME') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('spareparts.index', 'pe')">
                {{ __('Inventaris PE') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('request.list')">
                {{ __('Lihat Daftar Request') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
