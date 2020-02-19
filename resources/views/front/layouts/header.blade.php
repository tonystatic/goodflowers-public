<header class="header" role="banner" itemscope="" itemtype="http://schema.org/WPHeader">
    <div class="container-fluid">
        <div class="header__navbar row">
            <div class="col-4">
                <h1 class="logo header__logo" title="{{ config('app.name') }}">
                    <a id="headerLogoLink" class="header__logo-link" href="{{ route('front.index') }}">GoodFlowers</a>
                </h1>
            </div>
            <div class="col-8 text-right header__menu">
                <nav role="navigation" itemscope="" itemtype="http://schema.org/SiteNavigationElement" id="navbar-menu">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item">
                            <button class="header__btn btn btn-sm btn-primary" id="headerCreateGardenBtn">Завести свой сад</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

@push('scripts')
    <script>
        var jivo_onLoadCallback = function() {
            $('#headerCreateGardenBtn').click(function () {
                jivo_api.showProactiveInvitation("Если вы хотите такой же сад, расскажите о себе, и мы обязательно вам ответим!");
            });
        };
    </script>
@endpush
