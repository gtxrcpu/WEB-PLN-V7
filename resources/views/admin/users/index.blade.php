<x-layouts.app :title="'Manage Users — Admin'">
  {{-- Header with Back Button --}}
  <div class="mb-4 sm:mb-6">
    <a href="{{ route('admin.dashboard') }}"
      class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-sm border border-slate-200 mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
      <span class="text-sm font-medium">Kembali ke Dashboard</span>
    </a>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manage Users</h1>
        <p class="text-sm text-gray-600 mt-1">Kelola akun user dan role sistem</p>
      </div>
      <a href="{{ route('admin.users.create') }}"
        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span>Tambah User</span>
      </a>
    </div>
  </div>

  {{-- Success/Error Messages --}}
  @if(session('success'))
    <div
      class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg flex items-center gap-3 shadow-sm">
      <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <span class="text-green-800 font-medium">{{ session('success') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div
      class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-lg flex items-center gap-3 shadow-sm">
      <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <span class="text-red-800 font-medium">{{ session('error') }}</span>
    </div>
  @endif

  <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b-2 border-slate-200">
          <tr>
            <th class="px-4 sm:px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">User Info
            </th>
            <th class="px-4 sm:px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Contact
            </th>
            <th class="px-4 sm:px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Unit &
              Posisi</th>
            <th class="px-4 sm:px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Role</th>
            <th class="px-4 sm:px-6 py-4 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Actions
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($users as $user)
            <tr
              class="hover:bg-gradient-to-r hover:from-purple-50/50 hover:to-indigo-50/50 transition-all border-b border-slate-100 last:border-0">
              <td class="px-4 sm:px-6 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="font-bold text-gray-900 truncate">{{ $user->name }}</p>
                    @if($user->username)
                      <p class="text-sm text-purple-600 font-medium">{{ '@' . $user->username }}</p>
                    @else
                      <p class="text-sm text-gray-400 italic">No username</p>
                    @endif
                  </div>
                </div>
              </td>
              <td class="px-4 sm:px-6 py-4">
                <div class="flex flex-col gap-1">
                  <p class="text-sm text-gray-900 font-medium">{{ $user->email }}</p>
                  <p class="text-xs text-gray-500">{{ $user->created_at->format('d M Y') }}</p>
                </div>
              </td>
              <td class="px-4 sm:px-6 py-4">
                @if($user->unit)
                  <div class="flex flex-col gap-1">
                    <span
                      class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg w-fit
                          {{ $user->unit->code === 'INDUK' ? 'bg-gradient-to-r from-purple-100 to-violet-100 text-purple-700 ring-1 ring-purple-200' : 'bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-700 ring-1 ring-emerald-200' }}">
                      <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                          d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                          clip-rule="evenodd" />
                      </svg>
                      {{ $user->unit->name }}
                    </span>
                    @if($user->position)
                      <span
                        class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded bg-blue-50 text-blue-700 w-fit">
                        {{ ucfirst($user->position) }}
                      </span>
                    @endif
                  </div>
                @else
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-gradient-to-r from-slate-100 to-gray-100 text-slate-600 ring-1 ring-slate-200 w-fit">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                        clip-rule="evenodd" />
                    </svg>
                    Semua Unit
                  </span>
                @endif
              </td>
              <td class="px-4 sm:px-6 py-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg shadow-sm
                    @if($user->hasRole('superadmin')) bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-700 ring-1 ring-purple-200
                    @elseif($user->hasRole('leader')) bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 ring-1 ring-green-200
                    @else bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 ring-1 ring-blue-200 @endif">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    @if($user->hasRole('superadmin') || $user->hasRole('leader'))
                      <path fill-rule="evenodd"
                        d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.938l1-4H9.031z"
                        clip-rule="evenodd" />
                    @else
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd" />
                    @endif
                  </svg>
                  {{ strtoupper($user->getRoleNames()->first() ?? 'user') }}
                </span>
              </td>
              <td class="px-4 sm:px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.users.edit', $user) }}"
                    class="p-2.5 text-blue-600 hover:bg-blue-50 rounded-xl transition-all hover:scale-110 shadow-sm hover:shadow-md"
                    title="Edit User">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </a>
                  @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                      onsubmit="return confirm('⚠️ Yakin hapus user {{ $user->name }}?\n\nData user akan dihapus permanen!')">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="p-2.5 text-red-600 hover:bg-red-50 rounded-xl transition-all hover:scale-110 shadow-sm hover:shadow-md"
                        title="Hapus User">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </form>
                  @else
                    <div class="p-2.5 text-gray-300 cursor-not-allowed" title="Tidak bisa hapus diri sendiri">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                      </svg>
                    </div>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <p class="font-medium">Belum ada user</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($users->hasPages())
      <div class="px-6 py-4 border-t border-slate-200">
        {{ $users->links() }}
      </div>
    @endif
  </div>
</x-layouts.app>