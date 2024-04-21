@extends("layouts.master")
@section("contenu")



        <div class="tableau text-center">
            <hr style="border:1px dashed black">
            <h3 class="text-center"> {{$title}}</h3>
            <div style="margin-right: 150px;">
                <table >
                    <thead>
                        <th>Noms</th>

                        <th>Promotion</th>
                        {{-- <th>Departement</th> --}}
                        <th>Total_à_payer</th>
                        <th>Montant_Payé</th>
                        <th>Reste_à_payer</th>
                        <th>1e_Tranche</th>
                        <th>En ordre ?</th>


                    </thead>
                    <tbody>
                        @foreach ($queries as $query)

                            <tr>

                                <td>{{$query->nom. " ".$query->postnom. " ".$query->prenom}}</td>

                                <td>{{$query->classe}}</td>
                                {{-- <td>{{$query->departement}}</td> --}}
                                <td>{{$query->totalapayer. " FC"}}</td>
                                <td>{{$query->montantpaye. " FC"}}</td>
                                <td>{{$query->reste. " FC"}}</td>
                                <td>{{$query->Ptranche=(int)$query->Ptranche. " FC"}}</td>
                                <td class="text-center"> {{ $query->Ptranche > $query->montantpaye ? 'Non':'Oui'}}</td>

                            </tr>
                            @endforeach
                    </tbody>

                </table>

            </div>

        </div>


@endsection




