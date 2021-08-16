<h1>Olá, {{$user->name}}, tudo bem? Espero que sim!</h1>

<h3>Obrigado por sua inscrição!</h3>

<p>
    Faça bom proveito e excelentes compras! <br>
    Seu email cadastrado é: <strong>{{$user->email}}</strong>
    Sua senha é <strong>confidencial por questões de segurança</strong>
</p>

<hr>
Email enviado em {{date(d/m/Y H:i:s)}}.
