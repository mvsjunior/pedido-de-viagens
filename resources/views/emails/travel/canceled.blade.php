@component('mail::message')
# Solicitação de Viagem Cancelada

Olá, {{ $travelRequest->requester->name }}.

Sua solicitação de viagem foi **cancelada**.

**Motivo:** {{ $travelRequest->cancel_reason }}

Se tiver dúvidas, entre em contato com o seu gestor.

Obrigado,<br>
{{ config('app.name') }}
@endcomponent