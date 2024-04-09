<div>

    <x-filament::breadcrumbs :breadcrumbs="[
        '/admin/students' => 'Students',
        '' => 'List',
        ]" />
    <div class="flex justify-between mt-1">
        <div class="font-bold text-3xl">Students</div>
        <div>
            {{$data}}
        </div>
    </div>
    <div>
        <form wire:submit="save" class="w-full max-w-sm flex mt-2">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mt-3" for="">

                </label>

                <input class="shadow appearance-none border rounded w-full py-2 px-4 text-gray-700
                leading-tight focus:outline-none focus:shadow-outline" type="file" wire:model="file" id="">
            </div>
            <div class="flex items-center justify-between mb-3">
                <button class="text-white font-bold  py-2 px-4 rounded " style="background-color:rgba(215, 215, 150, 0.646); border-radius:10px; margin-bottom:5px; margin-left:3px;" type="submit">
                    Importer
                </button>

            </div>
            <div class="mt-3">
                @error('file')
                    <span class="text-danger-700">{{$message}}</span>
                @enderror
            </div>


        </form>
    </div>
</div>
