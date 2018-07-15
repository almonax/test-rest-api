$(document).ready(function () {
    var _token = $('meta[name="csrf-token"]').attr('content');
    var page = 2;


    $('.confirmation').on('click', function () {
        return confirm('Are you sure?');
    });

    $('#getNextUsers').on('click', function () {
        $(this).prop('disabled', true);
        $.ajax({
            url: '/client/users/getUsers',
            method: 'get',
            data: {
                _token: _token,
                page: page
            },
            success: function (response) {
                var body = response.body;
                var totalPages = response.totalPages;
                if (totalPages && page < totalPages) {
                    $('#getNextUsers').prop('disabled', false);
                }
                if (body.length) {
                    $('table#userList tbody').append(body);
                }
                page++;
                return true;
            },
            error: function (response) {
                console.log(response);
            }
        })
    });


    /*
     * Registration form
     */
    function getAvatarLoadPreview(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#reg-avatar-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#avatar").change(function() {
        getAvatarLoadPreview(this);
    });
});