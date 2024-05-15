@extends("layouts.master")
@section("contenu")


        <div class="tableau">
            <hr style="border:1px dashed black">
            <h3 class="text-center">Département : {{ $queries[0]->departement }} </h3>
            <h3 class="text-center"> Profil N° ...</h3>
            <h3 class="text-center"> {{$title}}</h3>
            <table class="table table-striped">
                <thead>
                    <th colspan="2">Identité_Complète_Etudiant</th>

                </thead>
                <tbody>


                        <tr>
                            <td style="width: 50%;">
                               Matricule : {{ $queries[0]->matricule}} <br>
                               Nom : {{ $queries[0]->nom}} <br>
                               Post Nom : {{ $queries[0]->postnom}} <br>
                               Prénom : {{ $queries[0]->prenom}} <br>
                               Genre : {{ $queries[0]->genre }} <br>
                               Date de Naissancce : {{ date("d/m/Y", strtotime($queries[0]->datenais)) }} <br>
                               Téléphone Etudiant : {{ $queries[0]->teletudiant}} <br>
                               Adresse Etudiant : {{ $queries[0]->adresse}} <br>
                               Province d'origine : {{ $queries[0]->province }} <br>
                               Territoire d'origine : {{ $queries[0]->territoire }} <br>
                               Territoire Ecole : {{ $queries[0]->territoireEcole }} <br>
                               Adresse Ecole : {{ $queries[0]->adresseEcole }} <br>
                               Dénomination Ecole : {{ $queries[0]->ecole }} <br>
                               Option faite au sécondaire : {{ $queries[0]->optionSecondaire }} <br>
                               Pourcentage examen d'état : {{ $queries[0]->pourcentage." %" }} <br>
                               Nom du père : {{ $queries[0]->nompere}} <br>
                               Nom de la mère : {{ $queries[0]->nommere}} <br>
                               Téléphone Tutaire : {{ $queries[0]->teltutaire}} <br>
                               Nationalité : {{ $queries[0]->nationalite}}
                            </td>
                            <td style="width: 50%;">
                                <h3 style="text-decoration:underline;" class="text-center">
                                    Photo de l'étudiant <br>

                                </h3>
                                <div class="text-center">
                                    @if ($queries[0]->photo != null)

                                        <img src="{{'storage/'.$queries[0]->photo}}" alt="photo de profil" class="rounded-circle img-fluid" style="width: 100px; height:100px;">
                                   @else
                                        <img src="{{'images/avatar.png'}}" alt="photo de profil" class="rounded-circle img-fluid" style="width: 100px; height:100px;">

                                    @endif
                                </div>
                                <h3 style="text-decoration:underline;" class="mt-3 mb-3 text-center">
                                    Eléments dossier <br>

                                </h3>
                                <div class="text-center">

                                    @if ($queries[0]->files != null)

                                        @foreach ($queries[0]->files as $file)

                                                 <img src="{{'storage/'.$file }}" alt="photo de profil" class="rounded img-fluid" width="100px">

                                          
                                        @endforeach
                                    @else
                                         <p class="fst-italic">Pas d'éléments de dossier</p>

                                    @endif


                                </div>
                            </td>

                        </tr>

                </tbody>

            </table>

        </div>
@endsection

