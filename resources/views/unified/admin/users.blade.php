@extends('layouts.unified')

@section('page-title', 'مدیریت کاربران')

@section('content')
    @php
        use Morilog\Jalali\Jalalian;
    @endphp

    <main class="flex-1 p-8">


        <div class="bg-white rounded-xl shadow p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 max-[728px]:hidden">
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider rounded-r-lg">نام و نام خانوادگی</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">نام کاربری</th>
                            @if(Auth::user()->role == 'master')
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">نقش</th>
                            @endif
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">عملکردها</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider rounded-l-lg">تاریخ عضویت</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $i = 0;
                        @endphp
                        @if ($users->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-8">
                                    <p class="text-gray-600">هیچ کاربری یافت نشد.</p>
                                </td>
                            </tr>
                        @else
                            @foreach ($users as $user)
                                <tr class="text-center max-[728px]:flex max-[728px]:flex-col max-[728px]:shadow-lg max-[728px]:rounded-lg max-[728px]:justify-center max-[728px]:mb-4 transition-transform transform group-hover:scale-105 max-[728px]:relative {{ $i % 2 == 0 ? 'max-[728px]:bg-[#e0e0dfc9]' : 'max-[728px]:bg-[#ecece5ea]' }}">

                                    <td class="px-6 py-4 whitespace-nowrap max-[728px]:hidden">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap max-[728px]:hidden">{{ $user->username }}</td>
                                    @if(Auth::user()->role == 'master')
                                    <td class="px-6 py-4 whitespace-nowrap max-[728px]:hidden">
                                        @switch($user->role)
                                            @case('user')
                                                <span class="text-blue-800">کاربر</span>
                                                @break
                                            @case('admin')
                                                <span class="text-green-800">ادمین</span>
                                                @break
                                            @default
                                                <span class="text-gray-800">نامشخص</span>
                                        @endswitch
                                    </td>
                                    @endif
                                    <td class="hidden px-6 py-4 max-[728px]:block m-auto text-3xl">
                                        <div class="flex flex-col gap-2">
                                            <span class="font-bold">{{ $user->name }}</span>
                                            <span class="text-lg">{{ $user->email }}</span>
                                            <span class="text-sm">
                                                @switch($user->role)
                                                    @case('user')
                                                        <span class="text-blue-800 bg-blue-100 px-2 py-1 rounded">کاربر</span>
                                                        @break
                                                    @case('admin')
                                                        <span class="text-green-800 bg-green-100 px-2 py-1 rounded">ادمین</span>
                                                        @break
                                                    @case('master')
                                                        <span class="text-purple-800 bg-purple-100 px-2 py-1 rounded">مدیر کل</span>
                                                        @break
                                                    @default
                                                        <span class="text-gray-800 bg-gray-100 px-2 py-1 rounded">نامشخص</span>
                                                @endswitch
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap flex items-center space-x-2 space-x-reverse justify-around max-[728px]:w-[45%] max-[728px]:m-auto">
                                        <a href="{{ route('unified.userdetail', $user->id) }}"
                                           class="text-blue-600 hover:text-blue-800 ml-2 flex">
                                            <button type="submit" class="w-fit pb-[4.5px]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </a>

                                        @if(Auth::user()->role === 'master' && $user->id !== Auth::user()->id)
                                            <form method="POST" action="{{ route('unified.deleteuser', ['id' => $user->id]) }}"
                                                  class="text-red-600 hover:text-red-800 ml-2 flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-fit pb-[4.5px]"
                                                        onclick="return confirm('آیا از حذف این کاربر مطمئن هستید؟')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if(Auth::user()->role === 'master')

                                @if ($user->role == 'admin')
                                    <a href="{{ route('unified.nadmin', ['id' => $user->id]) }}"
                                        class="text-blue-600 hover:text-blue-800 ml-2 flex ">

                                        <button type="submit" class="w-fit pb-[4.5px]">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                            </svg>

                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('unified.admin', ['id' => $user->id]) }}"
                                        class="text-blue-600 hover:text-blue-800 ml-2 flex ">

                                        <button type="submit" class="w-fit pb-[4.5px]">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                            </svg>

                                        </button>
                                    </a>
                                @endif

                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap max-[728px]:hidden">
                                        <span class="text-gray-600">
                                            {{ Jalalian::fromDateTime($user->created_at)->format('Y/m/d') }}
                                        </span>
                                    </td>

                                    <td class="hidden px-6 py-4 max-[728px]:block text-center">
                                        <span class="text-gray-600 max-[728px]:bg-gray-100 w-full h-full p-3 rounded-b-lg block">
                                            عضویت: {{ Jalalian::fromDateTime($user->created_at)->format('Y/m/d') }}
                                        </span>
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- <!-- Role Change Modal -->
    <div id="roleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">تغییر نقش کاربر</h3>
                <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="roleForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">نقش جدید</label>
                    <select name="role" id="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="user">کاربر</option>
                        <option value="admin">ادمین</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-4 space-x-reverse">
                    <button type="button" onclick="closeRoleModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        انصراف
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        تغییر نقش
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const name = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                const email = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                const role = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

                if (name.includes(searchTerm) || email.includes(searchTerm) || role.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    function changeRole(userId, currentRole) {
        document.getElementById('roleForm').action = `/unified/users/${userId}/role`;
        document.getElementById('role').value = currentRole;
        document.getElementById('roleModal').classList.remove('hidden');
        document.getElementById('roleModal').classList.add('flex');
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.add('hidden');
        document.getElementById('roleModal').classList.remove('flex');
    }
</script>
@endsection
