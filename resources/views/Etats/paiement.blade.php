@extends("layouts.master")
@section("contenu")



        <div class="tableau">
            <hr style="border:1px dashed black">
            <h3 class="text-center">Departement : {{ $queries[0]->departement }}</h3>
            <h3 class="text-center"> {{$title}}</h3>
            <table class="table table-striped">
                <thead>
                    <th>N°</th>
                    <th>Nom_Postnom_&_Prénom</th>
                    <th>Genre</th>
                    {{-- <th>Promotion</th> --}}
                    {{-- <th>Departement</th> --}}
                    <th>Montant</th>

                </thead>
                <tbody>
                    <?php $T_m=0;?>
                    @foreach ($queries as $query)

                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$query->nom." ".$query->postnom." ".$query->prenom}}</td>
                            <td>{{$query->genre}}</td>
                            {{-- <td>{{$query->classe}}</td> --}}
                            {{-- <td>{{$query->departement}}</td> --}}
                            <td>{{$query->montant. " FC"}}</td>
                        </tr>
                        <?php $T_m += $query->montant; ?>
                        @if ($loop->last)
                            <tr>
                                <td colspan="3">
                                    TOTAL GENERAL
                                </td>
                                <td>{{ $T_m." FC" }}</td>
                            </tr>

                        @endif
                    @endforeach
                </tbody>

            </table>

        </div>


@endsection




