document.addEventListener("DOMContentLoaded", function(event) {
    bindField('accept_name');
    bindField('accept_phone_number');
    bindField('accept_address');
    bindField('accept_url');
    bindField('accept_all');
});

function getData(fieldname){
    var data = {};
    data['_token'] = $('[name="_token"]').val();
    data['location_id'] = $("#location_id").val();
    
    data['name'] = $("#original_name").val();
    data['address'] = $("#original_address").val();
    data['external_web_url'] = $("#original_url").val();
    data['phone_number'] = $("#original_phone_number").val();
    switch(fieldname){
        case "accept_name":
            data['name'] = $("#suggestion_name").val();
            break;
        case "accept_phone_number":
            data['phone_number'] = $("#suggestion_phone_number").val();
            break;
        case "accept_address":
            data['address'] = $("#suggestion_address").val();
            break;
        case "accept_url":
            data['external_web_url'] = $("#suggestion_url").val();
            break;
        case "accept_all":
            data['name'] = $("#suggestion_name").val();
            data['external_web_url'] = $("#suggestion_url").val();
            data['address'] = $("#suggestion_address").val();
            data['phone_number'] = $("#suggestion_phone_number").val();
            break;
    }

    return data;
}

function bindField(tag){
    $("#"+ tag).click(function(){
        sendRequest(getData(tag), tag);
    })
}

function sendRequest(data,tag){
    $.ajax({
        url:"/location/management/edit",
        method:'post',
        data:data,
        success:function(){
            location.reload();
        },
        error:function(result){
            $("#" + tag).parent().prepend("<div class='alert alert-danger'>"
                                            + "Something wrong happens."
                                            + "</div>");
        }
    })
}