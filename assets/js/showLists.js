$(function () {
    $('#newList').click(function () {
        let uri=window.location.href;
        let name = prompt('Nom de la liste ?');
        if (name) {
            $.ajax({
                url: uri+'/ajaxAddList',
                type: "POST",
                dataType: "json",
                data: {
                    'name':name
                },
                async: true,
                success: function (data)
                {
                    console.log(data.id)
                    $('#List').append("<div class=\"col-lg-3 col-md-4 col-6 text-center mb-2\"><a href='" + uri + "/" + data.id + "'>" + name + "</a><button class=\"btn btn-danger\">×</button></div>");
                }
            });
        }
    });
});