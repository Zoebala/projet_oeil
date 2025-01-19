<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}
    @if(session("Annee"))
        <div >
            <h1 style="display: inline-block;">Année Académique</h1>
            <p style="font-style:italic; display: inline-block; margin-left:35%;"></p>



        </div>
        <h3>{{ session("Annee")[0]}}</h3>
    @else
        <h1 style="color:rgb(136, 60, 60); font-style:italic; text-align:center;">
            <p style="display: flex; justify-content:center;">

                <img src="{{ asset('./images/Warnings.png') }}" style="margin-right:10px;" alt="logo" width="40" height="28">
                <span style="margin-top:5px;">
                     Veuillez choisir une Année de Travail


                </span>


            </p>
        </h1>

    @endif
    </x-filament::section>
</x-filament-widgets::widget>
