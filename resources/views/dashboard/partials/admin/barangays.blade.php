@extends('dashboard.layout')

@section('title', 'Manage Barangays')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 animate-slideUp">
        <div>
            <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">🏢 Manage Barangays</h1>
            <p class="text-sm text-slate-500 font-medium">Onboard new barangays and manage existing LGUs in the LimpioZambo network.</p>
        </div>
        <button onclick="document.getElementById('modal-add-barangay').classList.remove('hidden')"
            class="mt-4 sm:mt-0 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm transition-colors">
            + Onboard Barangay
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold animate-slideUp">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold animate-slideUp">
            {{ session('error') }}
        </div>
    @endif

    {{-- Barangay Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-slideUp" style="animation-delay: 0.1s;">
        @forelse($barangays as $barangay)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-extrabold text-sm">
                            {{ strtoupper(substr($barangay->name, 0, 2)) }}
                        </div>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase rounded-md border border-emerald-200">Active</span>
                    </div>

                    <h3 class="text-base font-extrabold text-slate-900 mb-0.5">{{ $barangay->name }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-4">{{ $barangay->district }} District</p>

                    <div class="space-y-2 mb-5">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500 font-medium">Registered Users</span>
                            <span class="font-bold text-slate-800">{{ number_format($barangay->users_count) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500 font-medium">Active Trucks</span>
                            <span class="font-bold text-slate-800">{{ $barangay->trucks_count }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500 font-medium">Collection Points</span>
                            <span class="font-bold text-slate-800">{{ $barangay->collection_points_count }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.users') }}?barangay={{ $barangay->id }}"
                    class="block w-full text-center bg-slate-50 hover:bg-purple-50 hover:text-purple-700 text-slate-700 text-xs font-bold py-2.5 rounded-xl transition-colors border border-slate-200">
                    View Users
                </a>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                <p class="text-slate-400 text-sm font-semibold">No barangays onboarded yet. Click "Onboard Barangay" to get started.</p>
            </div>
        @endforelse
    </div>

    {{-- Add Barangay Modal --}}
    <div id="modal-add-barangay" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-slate-900">Onboard New Barangay</h3>
                <button onclick="document.getElementById('modal-add-barangay').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-700 transition-colors text-xs font-bold">✕ Close</button>
            </div>
            <form method="POST" action="{{ route('admin.barangays.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Barangay Name *</label>
                    <input type="text" name="name" required placeholder="e.g., Camino Nuevo"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">District *</label>
                    <input type="text" name="district" required placeholder="e.g., West District"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>
                <hr class="border-slate-100">
                <p class="text-[10px] text-slate-400 font-semibold">Optional: Create managing admin account</p>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Admin Full Name</label>
                    <input type="text" name="admin_name" placeholder="e.g., Brgy. Capt. Juan Dela Cruz"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Admin Email</label>
                    <input type="email" name="admin_email" placeholder="e.g., captain@calarian.gov"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Admin Password</label>
                    <input type="password" name="admin_password" placeholder="Minimum 6 characters"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>
                <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white text-xs font-extrabold py-3 rounded-xl transition-colors shadow-sm">
                    Onboard Barangay
                </button>
            </form>
        </div>
    </div>
@endsection