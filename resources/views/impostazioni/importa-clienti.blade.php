@include('partials.menu')

<div class="container">

    <h1>Importa clienti da Excel</h1>

    <form method="POST"
          action="/impostazioni/importa-clienti/anteprima"
          enctype="multipart/form-data">

        @csrf

        <p>
            File Excel<br>
            <input type="file"
                   name="file_excel"
                   accept=".xlsx,.xls,.csv"
                   required>
        </p>

        <button type="submit" class="btn btn-azione">
            Carica file
        </button>

    </form>

</div>