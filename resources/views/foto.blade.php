<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Foto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="post" action="{{ route('foto.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        <div>
                            <x-input-label for="name_foto" :value="__('Judul Foto')" />
                            <x-text-input id="name_foto" name="name_foto" type="text" class="mt-1 block w-full" required autofocus autocomplete="name_foto" />
                            <x-input-error class="mt-2" :messages="$errors->get('name_foto')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Album')" />
                            <textarea name="description" id="description" type="text" rows="3" required autofocus autocomplete="description" class="mt-1 p-2 border border-gray-300 rounded-md mt-1 block w-full"></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Foto')" />
                            <input type="file" name="image" id="image" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div>
                            <x-input-label for="album_id" :value="__('Album')" />
                            <select name="album_id" id="album_id" class="mt-1 block w-full" required>
                                <option value="">Pilih Album</option>
                                @foreach ($albums as $album)
                                <option value="{{ $album->id }}">{{ $album->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('album_id')" />
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
                    @if($fotos->count() > 0)
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Nama Album</th>
                                <th class="px-4 py-2">Judul Foto</th>
                                <th class="px-4 py-2">Deskripsi</th>
                                <th class="px-4 py-2">Gambar</th>
                                <th class="px-4 py-2">Tanggal Dibuat</th>
                                <th class="px-4 py-2">Tanggal Update</th>
                                <th class="px-4 py-2">Edit</th>
                                <th class="px-4 py-2">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Looping through each foto -->
                            @foreach ($fotos as $foto)

                            <tr>
                                <td class="border px-4 py-2">{{ $foto->album->name }}</td>
                                <td class="border px-4 py-2">{{ $foto->name }}</td>
                                <td class="border px-4 py-2">{{ $foto->description }}</td>
                                <td class="border px-4 py-2">
                                    <img src="{{ asset('storage/'.$foto->file_location) }}">

                                </td>
                                <td class="border px-4 py-2">{{ $foto->created_at }}</td>
                                <td class="border px-4 py-2">{{ $foto->updated_at }}</td>
                                <td class="border px-4 py-2">
                                    <a href="#" class="text-blue-500" onclick="editFoto('{{ $foto->id }}', '{{ $foto->name }}', '{{ $foto->description }}')">Edit</a>
                                </td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('foto.destroy', $foto->id) }}" method="POST">
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
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="edit-modal-title">Edit Foto</h3>
                                            <div class="mt-2">
                                                <form id="editFormFoto" action="{{ route('foto.update', ['foto' => $foto->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-4">
                                                        <label for="editName" class="block text-sm font-medium text-gray-700">Nama Foto</label>
                                                        <input type="text" name="name" id="editName" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="editDescription" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                                        <textarea name="description" id="editDescription" rows="3" class="mt-1 p-2 border border-gray-300 rounded-md w-full"></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50  px-4 py-2 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="button" onclick="submitEditFormFoto()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm">
                                                Simpan
                                            </button>

                                            <button type="button" onclick="closeEditModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm">
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
                    <p>Belum ada Foto.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function editFoto(id, name, description) {
            // Set value for input fields
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;

            // Set action for form             
            document.getElementById('editFormFoto').action = "{{ route('foto.update', ['foto' => ':id']) }}".replace(':id', id);

            // Show the modal
            document.getElementById('editModal').classList.remove('hidden');
        }


        function submitEditFormFoto() {
            // Submit the form
            document.getElementById('editFormFoto').submit();
        }

        function closeEditModal() {
            // Hide the modal
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</x-app-layout>