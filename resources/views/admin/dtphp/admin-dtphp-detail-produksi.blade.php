<x-admin-layout>

        <!-- Search dan Dropdown -->
        <div class="flex justify-between my-4 max-md:flex-col max-md:gap-4">
            <!-- Search Component -->
            <x-search></x-search>
        
            <!-- Filter -->
            <div class="flex justify-end">
                <div class="relative flex justify-end">
                <x-filter></x-filter>
        
                <!-- Modal Background -->
                <x-filter-modal>
                    <form action="" method="get">
                        <div class="space-y-4">
                            <!-- Pilih urutan -->
                            <div class="flex flex-col">
                                <label for="pilih_urutan" class="block text-sm font-medium text-gray-700 mb-1">Pilih Urutan</label>
                                <select name="order" class="w-full border border-gray-300 p-2 rounded-full bg-white shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>A - Z</option>
                                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Z - A</option>
                                </select>
                            </div>
                    
                            <!-- Pilih Tanaman -->
                            <div class="flex flex-col">
                                <label for="pilih_tanaman" class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanaman</label>
                                <select name="tanaman" id="pilih_tanaman" class="w-full border border-gray-300 p-2 rounded-full bg-white shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="">Semua Tanaman</option>
                                    @foreach ($commodities as $commodity)
                                        <option value="{{ $commodity->id }}" {{ request('tanaman') == $commodity->id ? 'selected' : '' }}>
                                            {{ $commodity->nama_tanaman }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <!-- Pilih periode -->
                            <div class="flex flex-col">
                                <label for="pilih_periode" class="block text-sm font-medium text-gray-700 mb-1">Pilih Periode</label>
                                <select name="periode" id="pilih_periode" class="w-full border border-gray-300 p-2 rounded-full bg-white shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="" disabled selected>Pilih Periode</option>
                                    @foreach ($periods as $index => $period)
                                        <option value="{{ $numberPeriods[$index] }}"
                                            {{ request('periode') == $numberPeriods[$index] ? 'selected' : '' }}>
                                            {{ $period }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    
                        <div class="w-full flex justify-end gap-3 mt-10">
                            <a href="{{ route('dtphp.detail.produksi') }}" class="bg-yellow-550 text-white rounded-lg w-20 p-1 text-center">Reset</a>
                            <button type="submit" class="bg-pink-650 text-white rounded-lg w-20 p-1">Cari</button>
                        </div>
                    </form>
                </x-filter-modal>
            </div>
            </div>
        </div>
        
        <main class="flex-1 p-6 max-md:p-4 bg-gray-10 border-gray-20 border-[3px] rounded-[20px]">
            <div class="w-full flex items-center gap-2 mb-4">
            <a href="{{ route('dtphp.produksi') }}" class="text-decoration-none text-dark flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h3 class="text-lg font-semibold text-center max-md:text-base">Data Volume Produksi Tahun 2025 (Ton)</h3>
            </div>
        
            <!-- Tombol Switch Produksi / Panen -->
            <div class="flex w-auto">
            <a href="{{ route('dtphp.detail.produksi') }}">
                <button class="text-pink-500 rounded-t-xl bg-white px-4 py-3 shadow-md text-sm border bg-gray-10 border-gray-20 {{ request()->routeIs('dtphp.detail.produksi') ? 'font-bold' : '' }} max-md:text-xs max-md:px-3 max-md:py-2">Volume Produksi</button>
            </a>
            <a href="{{ route('dtphp.detail.panen') }}">
                <button class="text-gray-400 rounded-t-xl bg-gray-100 px-4 py-3 shadow-md text-sm border bg-gray-10 border-gray-20 {{ request()->routeIs('dtphp.detail.panen') ? 'font-bold' : '' }} max-md:text-xs max-md:px-3 max-md:py-2">Luas Panen</button>
            </a>
            </div>
        
            <div class="bg-white p-6 max-md:p-4 rounded shadow-md relative z-10 overflow-x-auto">
            @if (isset($data_produksi))
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                <thead>
                    <tr>
                    <th class="px-9 max-md:px-2 py-2 whitespace-nowrap text-sm max-md:text-xs">Jenis Tanaman</th>
                    @php
                    $namaBulan = [
                        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                    ];
                    @endphp
                    @foreach ($namaBulan as $bulan)
                    <th class="px-4 max-md:px-1 py-2 text-center whitespace-nowrap text-sm max-md:text-xs">{{ $bulan }}</th>
                    @endforeach
                    <th class="px-5 max-md:px-1 py-2 text-sm max-md:text-xs">Total</th>
                    <th class="px-5 max-md:px-1 py-2 text-sm max-md:text-xs">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_produksi as $item)
                    <tr class="border-b hover:bg-gray-50">
                    <td class="p-2 max-md:p-1 text-sm max-md:text-xs text-center">{{ $item['nama_tanaman'] }}</td>
                    @for ($bulan = 1; $bulan <= 12; $bulan++)
                    <td class="px-5 max-md:px-1 py-2 text-center whitespace-nowrap text-sm max-md:text-xs">
                        @if (isset($item['produksi_per_bulan'][$bulan]))
                        {{ number_format($item['produksi_per_bulan'][$bulan], 1, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    @endfor
                    <td class="px-8 max-md:px-2 py-2 text-center font-semibold whitespace-nowrap text-sm max-md:text-xs">
                        {{ number_format(array_sum($item['produksi_per_bulan'] ?? []), 1, ',', '.') }}
                    </td>
                    <td class="p-2 max-md:p-1 flex justify-center gap-2">
                        <button class="editBtn bg-yellow-400 text-white rounded-md w-10 h-10 max-md:w-8 max-md:h-8" data-tanaman="{{ $item['nama_tanaman'] }}">
                        <i class="bi bi-pencil-square text-sm max-md:text-xs"></i>
                        </button>
                        <button class="deleteBtn bg-red-500 text-white rounded-md w-10 h-10 max-md:w-8 max-md:h-8" data-tanaman="{{ $item['nama_tanaman'] }}">
                        <i class="bi bi-trash-fill text-sm max-md:text-xs"></i>
                        </button>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>

                {{-- Modal --}}
                <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-40">
                    <div class="bg-white p-6 rounded-lg w-[90%] max-w-2xl shadow-lg relative">
                        <h2 class="text-xl font-semibold mb-4">Pilih Data untuk Di<span id="actionPlaceholder"></span></h2>
                        <div id="editDataList" class="space-y-4 max-h-96 overflow-y-auto mb-4"></div>
                        <div class="text-right" id="closeListModal">
                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Tutup</button>
                        </div>
                    </div>
                </div>
                
                {{-- Modal Delete --}}
                <div id="deleteModal" class="hidden w-full h-full">
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-40">
                        <div class="bg-white p-6 rounded-lg w-[25%] max-w-2xl shadow-lg relative">
                            <h2 class="text-xl font-semibold mb-6 text-center">Yakin menghapus data?</h2>
                            <div class="flex justify-around">
                                <button class="bg-pink-500 hover:bg-pink-400 text-white px-4 py-2 rounded-full" id="closeBtn">Tutup</button>
                                <button class="bg-pink-500 hover:bg-pink-400 text-white px-4 py-2 rounded-full" id="yesBtn">Yakin</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @else
            <div class="flex items-center justify-center h-64">
                <div class="text-center p-4 border-2 border-dashed border-gray-300 rounded-lg shadow-md bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-500 max-md:text-base">Data Not Found</h3>
                <p class="text-gray-400 max-md:text-sm">We couldn't find any data matching your request.</p>
                </div>
            </div>
            @endif
            </div>
        </main>
  
 
</x-admin-layout>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        $('#pilih_tanaman').on('change', function() {
            $('#pilih_periode').prop('disabled', false);
        });
    
        $('.editBtn').on('click', function() {
            const modal = $("#modal");
            modal.removeClass("hidden").addClass("flex");
    
            const jenisTanaman = $(this).data('tanaman');
    
            $.ajax({
                type: "GET",
                url: `/api/dtphp/${jenisTanaman}`,
                success: function(response) {
                    const data = response.data;
                    $('#editDataList').empty();
    
                    data.forEach(element => {
                        let listCard = `
                            <div class="border rounded-md p-4 shadow-sm flex items-center justify-between max-md:flex-col max-md:items-start max-md:gap-2">
                                <div class="max-md:w-full">
                                    <p class="text-sm text-gray-500 max-md:text-xs">Jenis Tanaman: <span class="font-medium">${element.nama_tanaman}</span></p>
                                    <p class="text-sm text-gray-500 max-md:text-xs">Volume Produksi: <span class="font-medium">${element.ton_volume_produksi} ton</span></p>
                                    <p class="text-sm text-gray-500 max-md:text-xs">Tanggal: <span class="font-medium">${element.tanggal_input}</span></p>
                                </div>
                                <a href="dtphp/${element.id}/edit" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 max-md:w-full max-md:text-center max-md:text-xs">Ubah</a>
                            </div>
                        `;
                        $('#editDataList').append(listCard);
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    
        $('.deleteBtn').on('click', function() {
            const modal = $("#modal");
            modal.removeClass("hidden").addClass("flex");
    
            const jenisTanaman = $(this).data('tanaman');
    
            $.ajax({
                type: "GET",
                url: `/api/dtphp/${jenisTanaman}`,
                success: function(response) {
                    const data = response.data;
                    $('#editDataList').empty();
    
                    data.forEach(element => {
                        let listCard = `
                            <div class="border rounded-md p-4 shadow-sm flex items-center justify-between max-md:flex-col max-md:items-start max-md:gap-2">
                                <div class="max-md:w-full">
                                    <p class="text-sm text-gray-500 max-md:text-xs">Jenis Tanaman: <span class="font-medium">${element.nama_tanaman}</span></p>
                                    <p class="text-sm text-gray-500 max-md:text-xs">Volume Produksi: <span class="font-medium">${element.ton_volume_produksi} ton</span></p>
                                    <p class="text-sm text-gray-500 max-md:text-xs">Tanggal: <span class="font-medium">${element.tanggal_input}</span></p>
                                </div>
                                <button data-id="${element.id}" class="btnConfirm bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 max-md:w-full max-md:text-center max-md:text-xs">Hapus</button>
                            </div>
                        `;
                        $('#editDataList').append(listCard);
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });

    $('#closeListModal').on('click', function() {
        $(this).closest('#modal').removeClass("flex").addClass("hidden");
    });
                                        
    $(document).on('click', '.btnConfirm', function() { 
        let dataId = $(this).data('id');
        $('#deleteModal').show();

        $('#yesBtn').off('click').on('click', function() {
            $.ajax({
                type: 'DELETE',
                url: `/api/dtphp/${dataId}`,
                success: function(data) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: `Data tanaman telah dihapus.`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: error
                    });
                }
            });

            $('#deleteModal').hide();
        });
    });

    $(document).on('click', '#closeBtn', function() {
        $('#deleteModal').hide();  
    });


    // Trigger Filter Modal
    function toggleModal() {
        const modal = document.getElementById('filterModal');
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }

    $("#filterBtn").on("click", function() {
        $("#filterModal").toggleClass("hidden");
    });
    // End Trigger Filter Modal
</script>