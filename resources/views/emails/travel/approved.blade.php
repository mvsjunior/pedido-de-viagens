@component('mail::message')
    Olá, {{ $travelRequest->requester->name }}!

    A sua solicitação de viagem foi **aprovada**.

    **Destino:** {{ $travelRequest->destination }}  
    **Data de ida:** {{ $travelRequest->departure_date }}  
    **Data de retorno:** {{ $travelRequest->return_date }}  

    @component('mail::button', ['url' => url('/travels/'.$travelRequest->id)])
    Ver Detalhes
    @endcomponent

    Obrigado,<br>
    {{ config('app.name') }}
@endcomponent
