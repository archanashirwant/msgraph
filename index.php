<ul class="navbar-nav justify-content-end">
    @if(isset($userName))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                @if(isset($user_avatar))
                    <img src="{{ $user_avatar }}" class="rounded-circle align-self-center mr-2" style="width: 32px;">
                @else
                    <i class="far fa-user-circle fa-lg rounded-circle align-self-center mr-2" style="width: 32px;"></i>
                @endif
                <p class="text-muted" style="margin: 0px; display: inline-block; max-width: 60%; vertical-align: bottom; overflow: hidden; text-overflow: ellipsis;">{{ $userEmail }}</p>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <h5 class="dropdown-item-text mb-0">{{ $userName }}</h5>
                <p class="dropdown-item-text text-muted mb-0">{{ $userEmail }}</p>
                <div class="dropdown-divider"></div>
                <a href="/mssignout" class="dropdown-item">Sign Out</a>
            </div>
        </li>
    @else
        <li class="nav-item">
            <p>To use Office365 you need to be logged in</p>
            <a href="/mssignin" class="nav-link">Sign In</a>
        </li>
    @endif
</ul>