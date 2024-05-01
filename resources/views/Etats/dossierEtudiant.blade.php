@extends("layouts.master")
@section("contenu")



        <div class="tableau text-center">
            <hr style="border:1px dashed black">
            <h3 class="text-center">Département : {{$queries[0]->departement }}</h3>
            <h3 class="text-center"> {{$title}}</h3>
            <div style="margin-right: 150px;">
                <table style="margin-left: 90px;">
                    <thead>
                        <th>N°</th>
                        <th>Nom_Postnom_&_Prénom</th>

                        <th>Genre</th>
                        <th>Date_de_naissance</th>



                    </thead>
                    <tbody>

                        @foreach ($queries as $query)

                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$query->nom." ".$query->postnom." ".$query->prenom}}</td>
                                <td>{{$query->genre}}</td>
                                <td>{{date('d/m/Y',$query->Naissance)}}</td>



                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>


@endsection




