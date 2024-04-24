<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Album') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="post" action="{{ route('album.store') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('post')

                        <div>
                            <x-input-label for="name_album" :value="__('Nama Album')" />
                            <x-text-input id="name_album" name="name_album" type="text" class="mt-1 block w-full" required autofocus autocomplete="name_album" />
                            <x-input-error class="mt-2" :messages="$errors->get('name_album')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Album')" />
                            <!-- <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" required autofocus autocomplete="description" /> -->
                            <!-- <label for="description" class="block text-sm font-medium text-gray-700">Description</label> -->

                            <textarea name="description" id="description" type="text" rows="3" required autofocus autocomplete="description" class="mt-1 p-2 border border-gray-300 rounded-md mt-1 block w-full"> </textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                             
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($albums->count() > 0)
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Nama Album</th>
                                <th class="px-4 py-2">Deskripsi Album</th>
                                <th class="px-4 py-2">Tanggal Dibuat</th>
                                <th class="px-4 py-2">Tanggal Update</th>
                                <th class="px-4 py-2">Edit</th>
                                <th class="px-4 py-2">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Isi tabel akan diisi dengan data dinamis dari database -->
                            <!-- Anda dapat menggunakan perulangan foreach untuk mengambil data dari database dan menampilkan di sini -->
                            @foreach ($albums as $album)
                            <tr>
                                <td class="border px-4 py-2">{{ $album->name }}</td>
                                <td class="border px-4 py-2">{{ $album->description }}</td>
                                <td class="border px-4 py-2">{{ $album->created_at }}</td>
                                <td class="border px-4 py-2">{{ $album->updated_at }}</td>
                                <td class="border px-4 py-2">
                                    <a href="#" class="text-blue-500" onclick="editAlbum('{{ $album->id }}', '{{ $album->name }}', '{{ $album->description }}')">Edit</a>
                                </td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('album.destroy', $album->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal -->
                            <div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="edit-modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                                    <!-- Modal content -->
                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="edit-modal-title">Edit Album</h3>
                                            <div class="mt-2">
                                                <form id="editForm" action="{{ route('album.update', ['album' => $album->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-4">
                                                        <label for="editName" class="block text-sm font-medium text-gray-700">Name</label>
                                                        <input type="text" name="name" id="editName" value="{{ $album->name }}" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="editDescription" class="block text-sm font-medium text-gray-700">Description</label>
                                                        <textarea name="description" id="editDescription" rows="3" class="mt-1 p-2 border border-gray-300 rounded-md w-full">{{ $album->description }}</textarea>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50  px-4 py-2 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="button" onclick="submitEditForm()" class=" w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm">
                                                Simpan
                                            </button>

                                            <button type="button" onclick="closeEditModal()" class=" w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>Belum ada album.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function editAlbum(id, name, description) {
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editForm').action = "{{ route('album.update', ['album' => ':id']) }}".replace(':id', id);
            document.getElementById('editModal').classList.remove('hidden');
        }

        function submitEditForm() {
            document.getElementById('editForm').submit();
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</x-app-layout>