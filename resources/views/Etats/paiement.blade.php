@extends("layouts.master")
@section("contenu")



        <div class="tableau">
            <hr style="border:1px dashed black">
            <h3 class="text-center"> {{$title}}</h3>
            <table class="table table-striped">
                <thead>
                    <th>Nom</th>
                    <th>Postnom</th>
                    <th>Prénom</th>
                    <th>Genre</th>
                    <th>Promotion</th>
                    <th>Departement</th>
                    <th>Montant</th>

                </thead>
                <tbody>
                    @foreach ($queries as $query)

                        <tr>

                            <td>{{$query->nom}}</td>
                            <td>{{$query->postnom}}</td>
                            <td>{{$query->prenom}}</td>
                            <td>{{$query->genre}}</td>
                            <td>{{$query->classe}}</td>
                            <td>{{$query->departement}}</td>
                            <td>{{$query->montant. " FC"}}</td>
                        </tr>
                        @endforeach
                </tbody>

            </table>

        </div>

  
@endsection




