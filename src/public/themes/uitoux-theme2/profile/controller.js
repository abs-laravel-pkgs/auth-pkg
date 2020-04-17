app.component('profile', {
    templateUrl: profile_page_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope, $route) {
        var self = this;
        self.hasPermission = HelperService.hasPermission;
        self.angular_routes = angular_routes;
        $scope.theme = theme;
        self.user_attchment_url = user_attchment_url;

        $http.get(
            laravel_routes['profile']
        ).then(function(response) {
            console.log(response.data);
            self.profile = response.data.profile_detail;
            if (self.profile.password_change == 'No') {
                self.switch_password = 'No';
                $("#hide_password").hide();
                $("#password").prop('disabled', true);
            } else {
                self.switch_password = 'Yes';
            }
            $rootScope.loading = false;
        });
        $scope.psw_change = function(val) {
            if (val == 'No') {
                $("#hide_password").hide();
                $("#password").prop('disabled', true);
            } else {
                $("#hide_password").show();
                setTimeout(function() {
                    $noty.close();
                }, 1000);
                $("#password").prop('disabled', false);
            }
        }

        var form_id = '#form';
        var v = jQuery(form_id).validate({
            ignore: '',
            rules: {
                'employee[alternate_mobile_number]': {
                    number: true,
                    minlength: 10,
                    maxlength: 12,
                },
                'password': {
                    required: function(element) {
                        if ($("#password_change").val() == 'Yes') {
                            return true;
                        } else {
                            return false;
                        }
                    },
                    minlength: 5,
                    maxlength: 16,
                },

            },
            submitHandler: function(form) {
                let formData = new FormData($(form_id)[0]);
                $('#submit').button('loading');
                $.ajax({
                        url: laravel_routes['updateProfile'],
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                    })
                    .done(function(res) {
                        if (res.success == true) {
                            custom_noty('success', res.message);
                            // $location.path('/auth-pkg/profile');
                            window.location.reload();
                            $('#submit').button('reset');

                            // $route.reload();
                        } else {
                            $('#submit').button('reset');
                            var errors = '';
                            for (var i in res.errors) {
                                errors += '<li>' + res.errors[i] + '</li>';
                            }
                            custom_noty('error', errors);
                        }
                    })
                    .fail(function(xhr) {
                        $('#submit').button('reset');
                        custom_noty('error', 'Something went wrong at server');
                    });
            }
        });
    }
});
