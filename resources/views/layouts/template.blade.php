@include('layouts.header')
@yield('content')
@include('layouts.footer_scripts')
@include('layouts.footer')
<div class="menu-backdrop"></div>
<div class="menu-backdrop2"></div>
@if(Auth::guard('user')->check())
@include('chat-box')
@endif
</body>
</html>