<!-- resources/views/components/sidebar.blade.php -->

<div class="left-side-bar">
    <div class="brand-logo">
        <a href="#">
            Tableau de bord
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div> 

    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                @auth
                    <!-- Onglet Mes comptes bancaires pour utilisateur -->
                    <li>
                        <a href="{{ route('dashboard_home') }}" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-house"></span>
                            <span class="mtext">Mes comptes bancaires</span>
                        </a>
                    </li>

                    <!-- Onglet Effectuer une opération pour utilisateur -->
                    <li>
                        <a href="{{ route('operations.create') }}" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-wallet"></span>
                            <span class="mtext">Effectuer une opération</span>
                        </a>
                    </li>
                    
                    
                @endauth

                <!-- Vérifie si l'utilisateur est administrateur -->
                @if(auth()->user()->role === 'admin')
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon dw dw-money"></span>
                            <span class="mtext">Gestion comptes</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('comptes.actifs') }}">Comptes actifs</a></li>
                            <li><a href="{{ route('comptes.inactifs') }}">Comptes inactifs</a></li>
                        </ul>
                    </li>

                    
                @endif
            </ul>
        </div>
    </div>
</div>
