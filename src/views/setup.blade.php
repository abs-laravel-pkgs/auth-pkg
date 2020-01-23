@if(config('auth-pkg.DEV'))
    <?php $auth_pkg_prefix = '/packages/abs/auth-pkg/src';?>
@else
    <?php $auth_pkg_prefix = '';?>
@endif

<script type="text/javascript">
    var login_page_template_url ="{{asset($auth_pkg_prefix.'/public/themes/'.$theme.'/pages/auth/login.html')}}";
</script>
<script type="text/javascript" src="{{asset($auth_pkg_prefix.'/public/themes/'.$theme.'/pages/auth/controller.js')}}"></script>
