<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Dashboard' }} - The Lobby</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config for RTL -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @livewireStyles
</head>
<body class="bg-gray-100 font-sans antialiased"
      x-data="{
          sidebarOpen: false,
          desktopSidebarOpen: localStorage.getItem('desktopSidebarOpen') !== 'false'
      }"
      x-init="
          $watch('desktopSidebarOpen', value => {
              localStorage.setItem('desktopSidebarOpen', value);
          });
      ">

    <!-- Sidebar -->
    <div class="fixed inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} z-50 w-64 bg-gray-900 transform transition-transform duration-300 flex flex-col"
         :class="{
             'translate-x-0': sidebarOpen || (desktopSidebarOpen && window.innerWidth >= 1024),
             '{{ app()->getLocale() === 'ar' ? 'translate-x-full' : '-translate-x-full' }}': !sidebarOpen && (!desktopSidebarOpen || window.innerWidth < 1024)
         }"
         x-init="
             window.addEventListener('resize', () => {
                 if (window.innerWidth >= 1024) {
                     sidebarOpen = false;
                 }
             });
         ">

        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-gray-800 flex-shrink-0">
            <h1 class="text-2xl font-bold text-white">The Lobby</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 mt-5 px-2 space-y-1 overflow-y-auto pb-4">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                {{ __('Dashboard') }}
            </a>


            <!-- Properties -->
            <a href="{{ route('admin.properties.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.properties.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                {{ __('Properties') }}
            </a>

            <!-- Units -->
            <a href="{{ route('admin.units.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.units.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"/>
                </svg>
                {{ __('Units') }}
            </a>

            <!-- Tenants -->
            <a href="{{ route('admin.tenants.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.tenants.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ __('Tenants') }}
            </a>

            <!-- Lease Contracts -->
            <a href="{{ route('admin.lease-contracts.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.lease-contracts.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('Lease Contracts') }}
            </a>

            <!-- Invoices -->
            <a href="{{ route('admin.invoices.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.invoices.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                </svg>
                {{ __('Invoices') }}
            </a>

            <!-- Payments -->
            <a href="{{ route('admin.payments.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.payments.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ __('Payments') }}
            </a>

            <!-- Maintenance Requests -->
            <a href="{{ route('admin.maintenance-requests.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.maintenance-requests.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ __('Maintenance') }}
            </a>

            <!-- Maintenance Jobs -->
            <a href="{{ route('admin.maintenance-jobs.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.maintenance-jobs.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ __('Jobs') }}
            </a>

            <!-- Service Providers -->
            <a href="{{ route('admin.service-providers.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.service-providers.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                {{ __('Providers') }}
            </a>

            <!-- User Profiles -->
            <a href="{{ route('admin.user-profiles.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.user-profiles.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                </svg>
                {{ __('User Profiles') }}
            </a>

            <!-- System Settings -->
            <a href="{{ route('admin.system-settings.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.system-settings.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ __('Settings') }}
            </a>

            <!-- Audit Logs -->
            <a href="{{ route('admin.audit-logs.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                {{ __('Audit Logs') }}
            </a>

            <!-- Messages -->
            <a href="{{ route('admin.messages.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.messages.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ __('Messages') }}
            </a>

            <!-- Notifications -->
            <a href="{{ route('admin.notifications.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.notifications.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                {{ __('Notifications') }}
            </a>

            <!-- Vacancy Management -->
            <a href="{{ route('admin.vacancies.dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.vacancies.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                {{ __('Vacancy Management') }}
            </a>

            <!-- Lease Renewals -->
            <a href="{{ route('admin.lease-renewals.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.lease-renewals.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                {{ __('Lease Renewals') }}
            </a>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                {{ __('Users') }}
            </a>

            {{-- TODO: Uncomment when routes are created
            <a href="{{ route('admin.contracts.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.contracts.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('Contracts') }}
            </a>

            <a href="{{ route('admin.invoices.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.invoices.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
                {{ __('Invoices') }}
            </a>

            <a href="{{ route('admin.maintenance.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.maintenance.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ __('Maintenance') }}
            </a>

            <a href="{{ route('admin.reports') }}"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition {{ request()->routeIs('admin.reports') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                {{ __('Reports') }}
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="transition-all duration-300 {{ app()->getLocale() === 'ar' ? 'lg:mr-64' : 'lg:ml-64' }}">

        <!-- Top Navigation Bar -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Desktop Sidebar Toggle Button -->
                    <button @click="desktopSidebarOpen = !desktopSidebarOpen"
                            class="hidden lg:block text-gray-600 hover:text-gray-900"
                            title="{{ __('Toggle Sidebar') }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <h2 class="text-xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h2>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4" x-data="{ userMenuOpen: false }">
                    <!-- Language Switcher -->
                    <div class="relative">
                        <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}"
                           class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50">
                            {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
                        </a>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                            <span class="{{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <div x-show="userMenuOpen"
                             @click.away="userMenuOpen = false"
                             x-cloak
                             class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                            {{-- TODO: Uncomment when auth is implemented
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Profile') }}</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Settings') }}</a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                            --}}
                            <a href="#" class="block px-4 py-2 text-sm text-gray-500">{{ __('Authentication Coming Soon') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            {{ $slot }}
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-cloak
         class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"></div>

    @livewireScripts

    <script>
        // Close mobile menu on route change
        document.addEventListener('livewire:navigated', () => {
            window.dispatchEvent(new CustomEvent('close-sidebar'));
        });
    </script>
</body>
</html>
