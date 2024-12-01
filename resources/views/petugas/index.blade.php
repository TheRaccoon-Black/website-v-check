@extends('layouts.auth.app')

@section('content')
    <div class="w-full p-4 bg-card rounded-lg border sm:p-8">
        <h5 class="mb-2 text-xl font-bold text-card-foreground">Daftar Petugas</h5>
        <p class="mb-5 text-sm text-muted-foreground">
            Daftar petugas ini menampilkan seluruh petugas yang terdaftar di dalam sistem.<br class="hidden sm:block"> Anda
            dapat mengelola data petugas
            disini.
        </p>
        <form method="GET" action="{{ route('petugas.index') }}">
            <div class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">
                <x-datatable :columns="['No', 'Nama Petugas', 'Regu', 'ID Petugas']" :rows="$petugas" :search="true" :fields="['nama_petugas', 'regu', 'petugas_id']" :colAction="['edit', 'delete']">
                    @slot('addButton')
                        <x-button :color="'secondary'" data-modal-target="modal-add" data-modal-toggle="modal-add">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Tambah Petugas
                        </x-button>
                    @endslot

                    @slot('filterButton')
                        <x-button id="filterDropdownButton" data-dropdown-toggle="filterDropdown" :color="'secondary'">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2" viewbox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Filter
                            <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </x-button>
                    @endslot

                    @slot('editScript')
                        <script>
                            function defaultEditFunction(data) {
                                const url = "{{ route('petugas.update', ['id' => '__id__']) }}".replace('__id__', data.id);

                                const form = document.getElementById('form-edit');
                                const inputNamaPetugas = document.getElementById('nama_petugas-edit');
                                const inputRegu = document.getElementById('regu-edit');
                                const inputPetugasId = document.getElementById('petugas_id-edit');

                                inputNamaPetugas.value = data.nama_petugas;
                                inputRegu.value = data.regu;
                                inputPetugasId.value = data.petugas_id;

                                form.action = url;
                            }
                        </script>
                    @endslot

                    @slot('deleteScript')
                        <script>
                            function defaultDeleteFunction(data) {
                                const url = "{{ route('petugas.destroy', ['id' => '__id__']) }}".replace('__id__', data.id);

                                const form = document.getElementById('form-delete');

                                form.action = url;
                            }
                        </script>
                    @endslot
                </x-datatable>
            </div>

            <div id="filterDropdown" class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
                    Filter Regu</h6>
                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                    @foreach ($grouped as $item)
                        <li class="flex items-center">
                            <input id="{{ $item->regu }}" type="checkbox" value="{{ $item->regu }}" name="regu[]"
                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="apple" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $item->regu }} ({{ $item->total }})
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </form>
    </div>

    {{-- 29/11/2024 --}}
    {{-- TODO: Form slot tambah button filter & add data. buat modal component reusable di blade, buat api dasar get data --}}

    {{-- 02/12/2024 --}}
    {{-- TODO: Animate modal, buat footer, buat breadcrumb, buat alert --}}

    {{-- modal add --}}
    <form method="POST" action="{{ route('petugas.store') }}" id="form-add">
        @csrf
        @method('POST')
        <x-modal :id="'modal-add'" :title="'Tambah Petugas'">
            @slot('form')
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="nama_petugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                            Petugas</label>
                        <input type="text" name="nama_petugas" id="nama_petugas-add"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukan Nama Petugas" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="regu" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Regu</label>
                        <input type="text" name="regu" id="regu-add"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukan Regu" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="petugas_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID
                            Petugas</label>
                        <input type="text" name="petugas_id" id="petugas_id-add"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukan ID Petugas" required="">
                    </div>
                </div>
                <x-button :type="'submit'" :color="'primary'" class="w-full">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd">
                        </path>
                    </svg>
                    Tambahkan Petugas
                </x-button>
            @endslot
        </x-modal>
    </form>


    {{-- modal edit --}}
    <form method="POST" action="{{ route('petugas.update', 0) }}" id="form-edit">
        @csrf
        @method('PUT')
        <x-modal :id="'modal-edit'" :title="'Edit Petugas'">
            @slot('form')
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="nama_petugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                            Petugas</label>
                        <input type="text" name="nama_petugas" id="nama_petugas-edit"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukan Nama Petugas" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="regu" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Regu</label>
                        <input type="text" name="regu" id="regu-edit"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukan Regu" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="petugas_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID
                            Petugas</label>
                        <input type="text" name="petugas_id" id="petugas_id-edit"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukan ID Petugas" required="">
                    </div>
                </div>
                <x-button :type="'submit'" :color="'primary'" class="w-full">
                    <svg class="me-1 -ms-1 w-5 h-5" width="24" height="24" fill="none" stroke="currentColor"
                        stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d='M4 21h16M5.666 13.187A2.28 2.28 0 0 0 5 14.797V18h3.223c.604 0 1.183-.24 1.61-.668l9.5-9.505a2.28 2.28 0 0 0 0-3.22l-.938-.94a2.277 2.277 0 0 0-3.222.001z' />
                    </svg>
                    Edit Petugas
                </x-button>
            @endslot
        </x-modal>
    </form>

    <form action="{{ route('petugas.destroy', 0) }}" method="post" id="form-delete">
        @csrf
        @method('DELETE')
        <x-modal :id="'modal-delete'" :title="'Hapus Petugas'">
            @slot('form')
                <div class="grid gap-4 mt-4 mb-8 grid-cols-2">
                    <div class="col-span-2 text-muted-foreground text-center">Apakah anda yakin ingin menghapus
                        petugas
                        ini?</div>
                </div>
                <div class="flex gap-4">
                    <x-button :type="'button'" :color="'secondary'" class="w-full" data-modal-toggle="modal-delete">
                        Tidak
                    </x-button>
                    <x-button :type="'submit'" :color="'danger'" class="w-full">
                        <svg class="me-1 -ms-1 w-5 h-5" width="24" height="24" fill="none" stroke="currentColor"
                            stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"
                            xmlns="http://www.w3.org/2000/svg" class="size-5">
                            <path
                                d='m18 7-.886 10.342c-.111 1.29-.166 1.936-.453 2.424a2.5 2.5 0 0 1-1.078.99c-.511.244-1.16.244-2.455.244h-2.256c-1.296 0-1.944 0-2.455-.244a2.5 2.5 0 0 1-1.078-.99c-.287-.488-.342-1.134-.453-2.424L6 7m-1.5-.5h4.615m0 0 .386-2.672c.112-.486.516-.828.98-.828h3.038c.464 0 .867.342.98.828l.386 2.672m-5.77 0h5.77m0 0H19.5' />
                        </svg>
                        Hapus Petugas
                    </x-button>
                </div>
            @endslot
        </x-modal>
    </form>
@endsection
