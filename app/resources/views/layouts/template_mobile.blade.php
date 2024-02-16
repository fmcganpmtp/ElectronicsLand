@include('layouts.header_mobile')
@yield('content')
@include('layouts.footer_scripts_mobile')
@include('layouts.footer_mobile')
<div class="menu-backdrop"></div>
<div class="menu-backdrop2"></div>
@if(Auth::guard('user')->check())
@include('chat-box_mobile')
@endif
</body>
</html>