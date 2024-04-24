<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($fotos as $foto)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <img src="{{ asset('storage/'.$foto->file_location) }}" alt="{{ $foto->name }}" class="w-full h-64 object-cover" style="width: 100%">

                    <div class="p-6">
                        <!-- <h2 class="text-xl font-semibold mb-2">{{ $foto->name }}</h2> -->
                        <!-- <button class="text-xl font-semibold mb-2 text-blue-500 cursor-pointer" data-toggle="modal" data-target="#descriptionModal{{ $foto->id }}">
                            {{ $foto->name }}
                        </button> -->
                        <button class="text-xl font-semibold mb-2 text-blue-500 cursor-pointer description-btn" data-foto-id="{{ $foto->id }}">
                            {{ $foto->name }}
                        </button>
                        <p class="text-gray-600 mb-4">{{ $foto->description }}</p>

                        <div class="flex items-center">
                            <div class="flex-1">
                                <span class="text-gray-500 font-semibold">
                                    {{ $foto->likes->count() }} Likes
                                </span>
                                <button class="likeButton like-btn text-red-500 ml-2" data-foto-id="{{ $foto->id }}">
                                    @if($foto->liked)
                                    Liked
                                    @else
                                    Unlike
                                    @endif
                                </button>
                                <button class="commentModal comment-btn text-gray-500 ml-2" data-foto-id="{{ $foto->id }}">
                                    Buka Comment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal untuk menampilkan deskripsi foto -->
                <div id="descriptionModal{{ $foto->id }}" class="modal fixed z-10 inset-0 overflow-y-auto hidden">
                    <div class="flex items-center justify-center min-h-screen">
                        <div class="fixed inset-0 bg-black opacity-50"></div>
                        <div class="relative bg-white rounded-lg w-1/2 p-8">
                            <!-- Judul modal -->
                            <h2 class="text-xl font-semibold mb-4">{{ $foto->name }}</h2>
                            <!-- Informasi tambahan -->
                            <div class="flex mb-4">
                                <div class="w-1/2 pr-4">
                                    <!-- Nama pengguna -->
                                    <p class="text-gray-600 mb-4">Pengguna: {{ $foto->user->username }}</p>
                                    <!-- Nama album -->
                                    <p class="text-gray-600 mb-4">Album: {{ $foto->album->name }}</p>
                                    <!-- Deskripsi Album -->
                                    <p class="text-gray-600 mb-4">Deskripsi Album : <br>{{ $foto->album->description }}</p>
                                    <!-- Deskripsi foto -->
                                    <p class="text-gray-600 mb-4">Deskripsi Foto : <br> {{ $foto->description }}</p>
                                    <!-- Tanggal dibuat -->
                                    <p class="text-gray-600 mb-1">Tanggal Dibuat: {{ $foto->created_at->format('d/m/Y') }}</p>
                                    <!-- Tanggal perbarui -->
                                    <p class="text-gray-600 mb-4">Tanggal Diperbarui: {{ $foto->updated_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="w-1/2">
                                    <!-- Gambar -->
                                    <img src="{{ asset('storage/'.$foto->file_location) }}" alt="{{ $foto->name }}" class="w-full h-auto">
                                </div>
                            </div>
                            <!-- Tombol tutup -->
                            <button class="text-gray-500 hover:text-gray-700 focus:outline-none mt-4 close-btn" onclick="closeDescriptionModal('{{ $foto->id }}')">Tutup</button>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk komentar -->
                <div id="commentModal{{ $foto->id }}" class="fixed z-10 inset-0 overflow-y-auto hidden">
                    <div class="flex items-center justify-center min-h-screen">
                        <div class="fixed inset-0 bg-black opacity-50"></div>
                        <div class="relative bg-white rounded-lg w-1/2 p-8">
                            <!-- Konten modal -->
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold">Comment Modal</h2>
                                <button class="text-gray-500 hover:text-gray-700 focus:outline-none close-comment-btn" onclick="closeCommentModal('{{ $foto->id }}')">Close</button>
                            </div>
                            <div>
                                @if($foto->comments->count() > 0)
                                <!-- Looping untuk menampilkan komentar-komentar -->
                                @foreach($foto->comments as $comment)
                                <p><strong>{{ $comment->username }}</strong> : {{ $comment->description }}</p>
                                @endforeach
                                @else
                                <p>Belum ada Komentar.</p>
                                @endif
                            </div>
                            <br>
                            <hr><br>
                            <form action="{{ route('comment.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="foto_id" value="{{ $foto->id }}">
                                <textarea name="comment" id="comment" class="w-full h-32 px-3 py-2 border border-gray-300 rounded-md mb-4" placeholder="Masukkan Komentar Anda dsini" required></textarea>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Fungsi untuk membuka modal berdasarkan id
        // function openModalById(modalId) {
        //     // Sembunyikan semua modal yang sedang terbuka
        //     $('.modal').modal('hide');
        //     // Tampilkan modal berdasarkan id yang diberikan
        //     $(modalId).modal('show');
        // }

        // // Periksa apakah komentar telah dikirim setelah halaman direload
        // $(window).on('load', function() {
        //     var commentSent = '{{ session('
        //     comment_sent ') }}';
        //     if (commentSent) {
        //         var modalId = '{{ session('
        //         modal_id ') }}'; // Dapatkan nilai modal_id dari session
        //         openModalById('#modal-comment-' + modalId); // Gabungkan nilai modal_id dengan selector
        //     }
        //     // hapus {{ session('modal_id') }}
        // });


        // Fungsi untuk menampilkan modal deskripsi
        function openDescriptionModal(id) {
            var modal = document.getElementById('descriptionModal' + id);
            modal.classList.remove('hidden');
        }

        // Fungsi untuk menyembunyikan modal deskripsi
        function closeDescriptionModal(id) {
            var modal = document.getElementById('descriptionModal' + id);
            modal.classList.add('hidden');
        }

        // Memilih semua tombol dengan kelas 'description-btn' dan menambahkan event listener
        document.querySelectorAll('.description-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = button.getAttribute('data-foto-id');
                openDescriptionModal(id);
            });
        });

        // Memilih semua tombol dengan kelas 'close-btn' di dalam modal dan menambahkan event listener
        document.querySelectorAll('.close-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = button.getAttribute('data-foto-id');
                closeDescriptionModal(id);
            });
        });




        // Fungsi untuk menampilkan modal komentar
        function openCommentModal(id) {
            var modal = document.getElementById('commentModal' + id);
            modal.classList.remove('hidden');
        }

        // Fungsi untuk menutup modal komentar
        function closeCommentModal(id) {
            var modal = document.getElementById('commentModal' + id);
            modal.classList.add('hidden');
        }

        // Memilih semua tombol dengan kelas 'comment-btn' dan menambahkan event listener untuk membuka modal
        document.querySelectorAll('.comment-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = button.getAttribute('data-foto-id');
                openCommentModal(id);
            });
        });

        // Memilih semua tombol dengan kelas 'close-btn' di dalam modal dan menambahkan event listener untuk menutup modal
        document.querySelectorAll('.close-comment-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = button.getAttribute('data-foto-id');
                closeCommentModal(id);
            });
        });


        // Panggil fungsi untuk memeriksa apakah user sudah menyukai foto saat dokumen dimuat
        $(document).ready(function() {

            $(document).on('click', '.likeButton', function() {
                var heartIcon = $(this).find('i');
                var fotoId = $(this).data('foto-id');
                // Pengecekan apakah ikon hati sudah berwarna merah atau belum
                if (heartIcon.hasClass('text-danger')) {
                    // Jika sudah berwarna merah, hilangkan warna merahnya
                    heartIcon.removeClass('text-danger');
                } else {
                    // Jika belum berwarna merah, tambahkan warna merahnya
                    heartIcon.addClass('text-danger');
                }
                // console.log(fotoId);
                // Kirim permintaan Ajax ke server untuk menyimpan data like
                $.ajax({
                    url: '/like',
                    method: 'POST',
                    data: {
                        foto_id: fotoId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Tindakan sukses (jika diperlukan)
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        location.reload();
                    }
                });
            });
        });
    </script>
</x-app-layout>