@extends("layouts.master")
@section("contenu")



        <div class="tableau text-center">
            <hr style="border:1px dashed black">
            <h3 class="text-center">Département : {{$queries[0]->departement }}</h3>
            <h3 class="text-center"> {{$title}}</h3>
            <div style="margin-right: 150px;">
                <table >
                    <thead>
                        <th>N°</th>
                        <th>Nom_Postnom_&_Prénom</th>

                        <th>Genre</th>
                        {{-- <th>Promotion</th> --}}
                        {{-- <th>Departement</th> --}}
                        <th>Total_à_payer</th>
                        <th>Montant_Payé</th>
                        <th>Reste_à_payer</th>


                    </thead>
                    <tbody>
                        <?php  $T_tap=0; $T_mp=0; $T_rp=0; ?>
                        @foreach ($queries as $query)

                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$query->nom." ".$query->postnom." ".$query->prenom}}</td>
                                <td>{{$query->genre}}</td>

                                <td>{{$query->totalapayer. " FC"}}</td>
                                <td>{{$query->montantpaye. " FC"}}</td>
                                <td>{{$query->reste. " FC"}}</td>

                            </tr>
                            <?php  $T_tap += $query->totalapayer; $T_mp += $query->montantpaye; $T_rp +=$query->reste; ?>
                            @if ($loop->last)
                                <tr>
                                    <td colspan="3">
                                        TOTAL GENERAL
                                    </td>
                                    <td>{{ $T_tap." FC" }}</td>
                                    <td>{{ $T_mp." FC" }}</td>
                                    <td>{{ $T_rp." FC" }}</td>
                                </tr>

                            @endif
                            @endforeach
                    </tbody>

                </table>

            </div>

        </div>


@endsection




