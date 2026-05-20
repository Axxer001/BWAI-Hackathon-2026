@extends('dashboard.layout')

@section('title', 'User Management')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 animate-slideUp">
        <div>
            <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">👥 User Management</h1>
            <p class="text-sm text-slate-500 font-medium">View, edit, or suspend any user account across the entire LimpioZambo platform.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search & Filter Bar --}}
    <form method="GET" action="{{ route('admin.users') }}" class="mb-6 flex flex-col sm:flex-row gap-3 animate-slideUp" style="animation-delay:0.05s;">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
                class="pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-purple-500 outline-none w-full shadow-sm">
        </div>
        <select name="role" class="border border-slate-200 rounded-xl text-sm text-slate-700 px-3 py-2 focus:ring-2 focus:ring-purple-500 outline-none shadow-sm">
            <option value="">All Roles</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Residents</option>
            <option value="barangay" {{ request('role') === 'barangay' ? 'selected' : '' }}>Barangay Admins</option>
            <option value="collector" {{ request('role') === 'collector' ? 'selected' : '' }}>Collectors</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Sys Admins</option>
        </select>
        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition-colors">
            Filter
        </button>
    </form>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Barangay</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors" id="user-row-{{ $user->id }}">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $user->full_name }}</p>
                                <p class="text-[10px] text-slate-400 font-mono">{{ $user->email }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'admin')
                                    <span class="px-2 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 text-[10px] font-bold uppercase rounded-md">Admin</span>
                                @elseif($user->role === 'barangay')
                                    <span class="px-2 py-0.5 bg-purple-50 text-purple-700 border border-purple-200 text-[10px] font-bold uppercase rounded-md">Barangay</span>
                                @elseif($user->role === 'collector')
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold uppercase rounded-md">Collector</span>
                                @else
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase rounded-md">Resident</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $user->barangay->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600">
                                        <span class="w-2 h-2 rounded-full bg-red-400"></span> Suspended
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    <button onclick="openEditModal('{{ $user->id }}', '{{ addslashes($user->full_name) }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->barangay_id }}')"
                                        class="text-[10px] font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-lg transition-colors">
                                        Edit
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="text-[10px] font-bold {{ $user->is_active ? 'bg-red-50 hover:bg-red-100 text-red-700' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }} px-2.5 py-1 rounded-lg transition-colors">
                                                {{ $user->is_active ? 'Suspend' : 'Restore' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No users found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Edit User Modal --}}
    <div id="modal-edit-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-slate-900">Edit User Account</h3>
                <button onclick="document.getElementById('modal-edit-user').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-700 transition-colors text-xs font-bold">✕ Close</button>
            </div>
            <form id="edit-user-form" method="POST" action="" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Full Name *</label>
                    <input type="text" id="edit-full-name" name="full_name" required
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Email *</label>
                    <input type="email" id="edit-email" name="email" required
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Role *</label>
                    <select id="edit-role" name="role" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="user">Resident</option>
                        <option value="barangay">Barangay Admin</option>
                        <option value="collector">Collector</option>
                        <option value="admin">System Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Barangay</label>
                    <select id="edit-barangay" name="barangay_id" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">None / City-wide</option>
                        @foreach($barangays as $barangay)
                            <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold py-3 rounded-xl transition-colors shadow-sm">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, fullName, email, role, barangayId) {
            document.getElementById('edit-full-name').value = fullName;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-role').value = role;
            document.getElementById('edit-barangay').value = barangayId || '';
            document.getElementById('edit-user-form').action = `/admin/users/${id}/update`;
            document.getElementById('modal-edit-user').classList.remove('hidden');
        }
    </script>
@endsection