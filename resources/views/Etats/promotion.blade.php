<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{'bootstrap5/css/bootstrap.min.css'}}">
    <script src="{{'bootstrap5/js/bootstrap.min.js'}}"></script>
    <title>{{$title}}</title>
    <style>
        table{
            border-collapse: collapse;
        }
        th,td{
            border: 1px solid black;

        }
    </style>
</head>
<body>
    <h1>Titre: {{$title}}</h1>
    <h2>Date: {{$date}}</h2>

    <table class="table table-striped-columns">
        <thead>
            <th>Nom</th>
            <th>Postnom</th>
            <th>Prénom</th>
        </thead>
        <tbody>
            @foreach ($Etudiants as $Etud)

                <tr>

                    <td>{{$Etud->nom}}</td>
                    <td>{{$Etud->postnom}}</td>
                    <td>{{$Etud->prenom}}</td>
                </tr>
            @endforeach
        </tbody>

    </table>



</body>
</html>
