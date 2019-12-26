$(function () {
    let uri=window.location.href;
    $('#newList').click(function () {
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
                    $('#List').append("<li class=\"col-lg-3 col-md-4 col-6 text-center mb-2\"><input type='hidden' id='"+data.id+"'><div class=\"row\"><a class=\"col-10\" href='" + uri + "/" + data.id + "'>" + name + "</a><button class=\"col-2 btn btn-danger del\">×</button></div></li>");
                }
            });
        }
    });

    $(document).on('click','.del',function(e){
        let supp = confirm('Voulez-vous vraiment supprimer cette liste ?');
        if(supp){
            let item = e.target;
            let id = item.parentNode.parentNode.firstElementChild.id;

            $.ajax({
                url: uri+'/ajaxRemoveList',
                type: "PUT",
                dataType: "json",
                data: {
                    'id':id
                },
                async: true,
                success: function (data)
                {
                    console.log(data)
                }
            })
            item.parentNode.parentNode.remove();
        }
    })
});