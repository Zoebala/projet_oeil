@extends("layouts.master")
@section("contenu")



        <div class="tableau text-center">
            <hr style="border:1px dashed black">
            {{-- <h3 class="text-center">Departement : {{ $queries[0]->departement }}</h3> --}}
            <h3 class="text-center"> {{$title}}</h3>
            <div style="margin-right: 150px;">
                <table >
                    <thead>
                        <th>N°</th>
                        <th width="100px">Promotion</th>
                        <th>Montant_a_Payer</th>
                        <th>Effectif</th>
                        <th>Montant_attendu_Par_promotion</th>


                    </thead>
                    <tbody>
                        <?php $T_mp=0; $T_eff=0;?>
                        @foreach ($queries as $query)

                            <tr>
                                <td>{{$loop->index+1}}</td>

                                <td width="100px">{{$query->Promotion}}</td>
                                {{-- <td>{{$query->departement}}</td> --}}
                                <td>{{$query->Montantapayer. " FC"}}</td>
                                <td>{{$query->Effectif}}</td>
                                <td>{{$query->MontantPromotion. " FC"}}</td>

                            </tr>
                            <?php  $T_mp += $query->MontantPromotion; $T_eff +=$query->Effectif; ?>
                            @if ($loop->last)
                                <tr>
                                    <td colspan="3">
                                        TOTAl GENERAL
                                    </td>

                                    <td>{{ $T_eff }}</td>
                                    <td>{{ $T_mp." FC" }}</td>
                                </tr>

                            @endif
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>


@endsection




