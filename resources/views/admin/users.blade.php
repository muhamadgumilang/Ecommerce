<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-slate-800">Manajemen Pengguna</h2>
                <p class="text-sm text-slate-500 mt-1">Pantau akun admin, petugas, dan customer.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
                class="text-sm font-semibold text-blue-600 hover:text-blue-700">Kembali ke Dashboard</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                            <tr>
                                <th class="px-6 py-3">Pengguna</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Telepon</th>
                                <th class="px-6 py-3">Peran</th>
                                <th class="px-6 py-3">Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($users as $user)
                                @php
                                    $roleStyles = [
                                        'Admin' => 'bg-violet-50 text-violet-700 border-violet-200',
                                        'Seller' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Customer' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ];
                                    $roleLabels = ['Seller' => 'Petugas'];
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                            <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $user->phone ?? '-' }}</td>
                                    <td class="px-6 py-4"><span
                                            class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $roleStyles[$user->role] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">{{ $roleLabels[$user->role] ?? $user->role }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $user->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada pengguna.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="p-6 border-t border-slate-100">{{ $users->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
