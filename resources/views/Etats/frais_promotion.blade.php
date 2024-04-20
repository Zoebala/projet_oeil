@extends("layouts.master")
@section("contenu")



        <div class="tableau text-center">
            <hr style="border:1px dashed black">
            <h3 class="text-center"> {{$title}}</h3>
            <div style="margin-right: 150px;">
                <table >
                    <thead>
                        <th>Nom</th>
                        <th>Postnom</th>
                        <th>Prénom</th>
                        <th>Genre</th>
                        <th>Promotion</th>
                        {{-- <th>Departement</th> --}}
                        <th>Total_à_payer</th>
                        <th>Montant_Payé</th>
                        <th>Reste_à_payer</th>


                    </thead>
                    <tbody>
                        @foreach ($queries as $query)

                            <tr>

                                <td>{{$query->nom}}</td>
                                <td>{{$query->postnom}}</td>
                                <td>{{$query->prenom}}</td>
                                <td>{{$query->genre}}</td>
                                <td>{{$query->classe}}</td>
                                {{-- <td>{{$query->departement}}</td> --}}
                                <td>{{$query->totalapayer. " FC"}}</td>
                                <td>{{$query->montantpaye. " FC"}}</td>
                                <td>{{$query->reste. " FC"}}</td>

                            </tr>
                            @endforeach
                    </tbody>

                </table>

            </div>

        </div>


@endsection




