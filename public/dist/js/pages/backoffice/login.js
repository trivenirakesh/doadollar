$(".loading-btn").hide();
$("#loginFrm").validate({
    rules: {
        mobile: {
            required: true,
            email : true,
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
            beforeSend: function() {
                $(".loading-btn").show();
                $(".submit-btn").hide();
            },
            success: function (res) {
                console.log(res);
                if(res.status == 200){
                    toastr.success(res.message);
                    window.location.href = APP_URL+'/dashboard';
                }else{
                    $(".loading-btn").hide();
                    $(".submit-btn").show();
                    let validationMessage = res.message;
                    let validationType = $.type(validationMessage);
                    if (validationType == 'object'){
                        for (const key in validationMessage) {
                            if (validationMessage.hasOwnProperty(key)) {
                                toastr.error(validationMessage[key]);
                            }
                          }
                    }else{
                        toastr.error(validationMessage);
                    }
                }
            }
        })
    }
})