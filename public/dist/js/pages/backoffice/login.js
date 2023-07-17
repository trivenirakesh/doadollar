
$("#loginFrm").validate({
    rules: {
        mobile: {
            required: true,
            number: true
        },
        password: "required"
    },
    messages: {
        mobile: {
            required: "Please enter mobile",
            number: "Mobile must be numeric"
        },
        password: "Please enter password",
    }
});

$("#loginFrm").submit(function (e) {
    e.preventDefault();
    if ($(this).valid()) {
        $.ajax({
            url: APP_URL + '/adminlogin',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            success: function (res) {
                console.log(res);
                if(res.status == 200){
                    toastr.success(res.message);
                    window.location.href = APP_URL+'/system/dashboard';
                }else{
                    let validationMessage = res.message;
                    let validationType = $.type(validationMessage);
                    if (validationType == 'object'){
                        $(validationMessage).each(function(key,val){
                            console.log(val);
                        })
                    }
                }
            }
        })
    }
})