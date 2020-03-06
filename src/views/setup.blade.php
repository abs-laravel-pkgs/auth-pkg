@if(config('auth-pkg.DEV'))
    <?php $auth_pkg_prefix = '/packages/abs/auth-pkg/src';?>
@else
    <?php $auth_pkg_prefix = '';?>
@endif

<script type="text/javascript">
	app.config(['$routeProvider', function($routeProvider) {

	    $routeProvider.
	    when('/', {
	        template: '<login></login>',
	        title: 'Login',
	    }).
	    when('/login', {
	        template: '<login></login>',
	        title: 'Login',
	    }).
	    when('/forgot-password', {
	        template: '<forgot-password></forgot-password>',
	        title: 'Forgot Password',
	    }).
	    when('/reset-password', {
	        template: '<reset-password></reset-password>',
	        title: 'Reset Password',
	    });

	}]);

    var login_page_template_url ="{{asset($auth_pkg_prefix.'/public/themes/'.$theme.'/auth/login.html')}}";
</script>
<script type="text/javascript" src="{{asset($auth_pkg_prefix.'/public/themes/'.$theme.'/auth/controller.js')}}"></script>
