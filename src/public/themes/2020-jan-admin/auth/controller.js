app.component('login', {
    templateUrl: login_page_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope) {
        var self = this;
        self.hasPermission = HelperService.hasPermission;
        self.angular_routes = angular_routes;
        var form_id = '#login-form';
        var v = jQuery(form_id).validate({
            ignore: '',
            rules: {
                'username': {
                    required: true,
                    minlength: 3,
                    maxlength: 255,
                },
                'password': {
                    required: true,
                    minlength: 3,
                    maxlength: 255,
                },
            },
            submitHandler: function(form) {
                let formData = new FormData($(form_id)[0]);
                $('#submit').button('loading');
                $.ajax({
                        url: laravel_routes['login'],
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                    })
                    .done(function(res) {
                        if (!res.success) {
                            showErrorNoty(res);
                            $('#submit').button('reset');
                            return;
                        }
                        window.location.reload();
                    })
                    .fail(function(xhr) {
                        $('#submit').button('reset');
                        custom_noty('error', 'Something went wrong at server');
                    });
            }
        });
    }
});
