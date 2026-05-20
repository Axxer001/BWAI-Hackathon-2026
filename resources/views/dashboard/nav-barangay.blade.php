<li>
    <a href="/dashboard/schedules" class="group flex items-center gap-3 px-6 py-3 text-sm font-medium border-l-4 transition-all {{ request()->is('dashboard/schedules') ? 'bg-white/5 text-white border-amber-500' : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-amber-500' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->is('dashboard/schedules') ? 'text-amber-400' : 'text-slate-500 group-hover:text-amber-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Route Schedules
    </a>
</li>
<li>
    <a href="/dashboard/fleet" class="group flex items-center gap-3 px-6 py-3 text-sm font-medium border-l-4 transition-all {{ request()->is('dashboard/fleet') ? 'bg-white/5 text-white border-blue-500' : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-blue-500' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->is('dashboard/fleet') ? 'text-blue-400' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Track Fleet & Trucks
    </a>
</li>
<li>
    <a href="/dashboard/points-manage" class="group flex items-center gap-3 px-6 py-3 text-sm font-medium border-l-4 transition-all {{ request()->is('dashboard/points-manage') ? 'bg-white/5 text-white border-green-500' : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-green-500' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->is('dashboard/points-manage') ? 'text-green-400' : 'text-slate-500 group-hover:text-green-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4"/>
        </svg>
        Collection Points
    </a>
</li>
<li>
    <a href="/dashboard/tickets-missed" class="group flex items-center gap-3 px-6 py-3 text-sm font-medium border-l-4 transition-all {{ request()->is('dashboard/tickets-missed') ? 'bg-white/5 text-white border-amber-500' : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-amber-500' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->is('dashboard/tickets-missed') ? 'text-amber-400' : 'text-slate-500 group-hover:text-amber-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        Missed Pickups Desk
    </a>
</li>
<li>
    <a href="/dashboard/tickets-violations" class="group flex items-center gap-3 px-6 py-3 text-sm font-medium border-l-4 transition-all {{ request()->is('dashboard/tickets-violations') ? 'bg-white/5 text-white border-red-500' : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-red-500' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->is('dashboard/tickets-violations') ? 'text-red-400' : 'text-slate-500 group-hover:text-red-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"/>
        </svg>
        Violation Reports
    </a>
</li>