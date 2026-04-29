@include('partials.menu')

<h1>Nuovo Fornitore</h1>

<form method="POST" action="/fornitori">
@csrf

<input type="text" name="ragione_sociale" placeholder="Nome fornitore" required>

<input type="text" name="referente" placeholder="Referente">

<input type="text" name="telefono" placeholder="Telefono">

<input type="email" name="email" placeholder="Email">

<textarea name="note" placeholder="Note"></textarea>

<button>Salva</button>

</form>