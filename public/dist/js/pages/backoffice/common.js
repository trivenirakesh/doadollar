let APP_URL = $("#appurl").val();

// common function for open modal
function globalFunctionModal(moduleName = '',operation,id= null){
    $.ajax({
        url : APP_URL+'/createmodelhtml',
        type : 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data : {'modulename' : moduleName,'operation':operation},
        success : function(res){
            $("#globalCrudModal").modal('show');
            $(".createupdatemodal").html(res);
            $("#globalCrudModalTitle").text(operation);
            $(".globalCrudModalSubmitBtn").text(operation);
        }
    })
}

