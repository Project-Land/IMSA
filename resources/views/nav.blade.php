

<div class="row">

    <div class="col">

        <a type="button" href="/" class="btn btn-secondary rounded-0 text-white col-sm-10 col-md-2 mb-1">Standardi</a>

        <div class="btn-group col-sm-12 col-md-2 mb-1">
            <button type="button" class="btn btn-secondary dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dokumentacija
            </button>
            <div class="dropdown-menu">
                <!-- Dropdown menu links -->
                <a class="dropdown-item" href="/rules-of-procedures">Poslovnik</a>
                <a class="dropdown-item" href="/policies">Politike</a>
                <a class="dropdown-item" href="/procedures">Procedure</a>
                <a class="dropdown-item" href="/manuals">Uputstva</a>
                <a class="dropdown-item" href="/forms">Obrasci</a>
            </div>
        </div>

        <div class="btn-group col-sm-12 col-md-2 mb-1">
            <button type="button" class="btn btn-secondary dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sistemski procesi
            </button>
            <div class="dropdown-menu">
                <!-- Dropdown menu links -->
                <a class="dropdown-item" href="/risk-management">Upravljanje rizikom</a>
                <a class="dropdown-item" href="/internal-check">Interne provere</a>
                <a class="dropdown-item" href="/corrective-measures">Neusaglašenosti i korektivne mere</a>
                <a class="dropdown-item" href="/trainings">Obuke</a>
                <a class="dropdown-item" href="/goals">Ciljevi</a>
                <a class="dropdown-item" href="/suppliers">Odobreni isporučioci</a>
                <a class="dropdown-item" href="/stakeholders">Zainteresovane strane</a>
                <a class="dropdown-item" href="/complaints">Upravljanje reklamacijama</a>
                <a class="dropdown-item" href="/management-system-reviews">Preispitivanje sistema menadžmenta</a>
            </div>
        </div>

        <div class="btn-group col-sm-12 col-md-2 mb-1">
            <button type="button" class="btn btn-secondary dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sektori
            </button>
            <div class="dropdown-menu">
                <!-- Dropdown menu links -->
                <a class="dropdown-item" href="/sectors">Lista sektora</a>
                @can('create', App\Models\Sector::class)<a class="dropdown-item" href="/sectors/create">Dodaj sektor</a>@endcan
            </div>
        </div>

    </div>
    
</div>