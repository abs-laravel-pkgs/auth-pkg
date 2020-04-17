@if(config('auth-pkg.DEV'))
    <?php $auth_pkg_prefix = '/packages/abs/auth-pkg/src';?>
@else
    <?php $auth_pkg_prefix = '';?>
@endif

<script type="text/javascript">
	app.config(['$routeProvider', function($routeProvider) {

	    $routeProvider.
	    when('/auth-pkg/profile', {
	        template: '<profile></profile>',
	        title: 'Profile',
	    });

	}]);

    var user_attchment_url = "{{asset('/storage/app/public/user-profile-images')}}";
    var profile_page_template_url ="{{asset($auth_pkg_prefix.'/public/themes/'.$theme.'/profile/form.html')}}";
</script>
<script type="text/javascript" src="{{asset($auth_pkg_prefix.'/public/themes/'.$theme.'/profile/controller.js')}}"></script>
