<x-tamu-layout title="Pasar">
    <div class="w-full py-16 px-6 md:px-0 flex flex-col justify-center items-center text-pink-650">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-6 text-center">Harga Pangan <span id="namaPasar"></span></h1>
        <h5 class="text-md md:text-xl text-shadow mb-5 text-center">Harga rata-rata dibandingkan dengan hari sebelumnya <b>{{ $kemarin }}</b></h5>
        <form action="{{ route('tamu.pasar.search') }}" class="w-full flex justify-center">
            @csrf
            <div class="w-full max-w-md bg-yellow-450 flex justify-between items-center pl-6 pr-3 py-1 rounded-full">
                <span class="bi bi-search mr-5"></span>
                <input
                    type="text"
                    placeholder="Cari Pasar"
                    id="pasar"
                    name="pasar"
                    class="border-none outline-none focus:bg-transparent text-pink-650 placeholder-pink-650 bg-transparent flex-1">
                <button type="button" class="bg-pink-650 rounded-full py-2 w-24 text-white" id="submitBtn">Cari</button>
            </div>
        </form>
    </div>
    {{-- Daftar Komoditas per Pasar --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-items-center px-6 md:mx-32" id="comoditiesList">
        {{-- Diisi pakai AJAX --}}
    </div>
</x-tamu-layout>

<script>
    function capitalize(str) {
        return str.split(" ").map(word =>
            word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
        ).join(" ");
    }
    
    
    function loadKomoditas() {
        $.ajax({
            type: "GET",
            url: "{{ route('api.pasar.search') }}",
            data: {
                pasar: $('#pasar').val(),
            },
            success: function (pasar) {
                console.log(pasar);
                
                let datas = pasar.data;
                let namaPasar = pasar.inputPasar;

                $('#comoditiesList').empty();

                if (datas.length === 0) {
                    $('#comoditiesList').html(`
                        <div class="flex flex-col items-center justify-center col-span-full px-28 py-10 text-center text-gray-500 border-4 rounded-xl border-gray-400" id="noDataMessage">
                            <div class="text-4xl mb-6 ">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>
                            <h3 class="text-2xl font-semibold mb-2">Data Tidak Ditemukan</h3>
                            <p class="text-sm">Tidak ada pasar yang tersedia.</p>
                        </div>
                    `);
                } else {
                    $('#namaPasar').html(`di ${capitalize(namaPasar)}`);
                    $.each(datas, function (index, value) {
                        let statusSpan = '';
    
                        if (value.status == 'Stabil') {
                            statusSpan = `
                                <span class="flex justify-center items-center bg-blue-200 w-full rounded-full p-2 text-blue-600 font-extrabold gap-3">
                                    <i class="bi bi-circle font-extrabold"></i>
                                    ${value.status} Rp. ${value.selisih}
                                </span>`;
                        } else if (value.status == 'Naik') {
                            statusSpan = `
                                <span class="flex justify-center items-center bg-green-200 w-full rounded-full p-2 text-green-600 font-extrabold gap-3">
                                    <i class="bi bi-arrow-up font-extrabold"></i>
                                    ${value.status} Rp. ${value.selisih}
                                </span>`;
                        } else if (value.status == 'Turun') {
                            statusSpan = `
                                <span class="flex justify-center items-center bg-red-200 w-full rounded-full p-2 text-red-600 font-extrabold gap-3">
                                    <i class="bi bi-arrow-down font-extrabold"></i>
                                    ${value.status} Rp. ${value.selisih}
                                </span>`;
                        } else {
                            statusSpan = `
                                <span class="flex justify-center items-center bg-slate-200 w-full rounded-full p-2 text-slate-600 font-extrabold gap-3">
                                    ${value.status}
                                </span>`;
                        }
    
                        let card = `
                            <div class="bg-white rounded-3xl shadow-md overflow-hidden border h-auto w-72 py-3 px-1">
                                <div class="h-[40vw] sm:h-40 md:h-44 lg:h-48 flex justify-center items-center overflow-hidden">
                                    <img src="${value.gambar_komoditas ? '/storage/' + value.gambar_komoditas : '/storage/img/landscape-placeholder.svg'}" alt="komoditas" class="object-cover ${value.gambar_komoditas ? '' : 'w-full h-full'}">
                                </div>
                                <div class="p-4 flex flex-col items-center gap-1">
                                    <p class="text-gray-600">${value.komoditas}</p>
                                    <h3 class="text-2xl font-extrabold">Rp. ${value.rata_rata_hari_ini}/kg</h3>
                                    <p class="text-lg mb-3">${value.pasar}</p>
                                    ${statusSpan}
                                </div>
                            </div>`;
    
                        $('#comoditiesList').append(card); 
                    });
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                let message = '';

                $.each(errors, function (key, value) {
                    message += value + '<br>';
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: message
                });
            }
        });
    }

    $('#pasar').keypress(function (event) {
        if (event.which === 13) {
            event.preventDefault();
            loadKomoditas();
        }
    });

    $('#submitBtn').on('click', function () {
        loadKomoditas();
    });

</script>