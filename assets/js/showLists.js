import('../css/showLists.scss');
$(function () {
    let uri=window.location.href;
    $('#newList').click(function () {
        let listName = prompt('Nom de la liste ?');
        if (listName) {
            $.ajax({
                url: uri+'/ajaxAddList',
                type: "POST",
                dataType: "json",
                data: {
                    'listName':listName
                },
                async: true,
                success: function (data)
                {
                    $('#List').append("<li class=\"col-lg-3 col-md-4 col-6 text-center mb-2\"><input type='hidden' id='"+data.listId+"'><div class=\"row\"><a class=\"col-10\" href='" + uri + "/" + data.listId + "'>" + listName + "</a><button class=\"col-2 btn btn-danger del\">×</button></div></li>");
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
                    'listId':id
                },
                async: true,
                success: function (data)
                {

                }
            })
            item.parentNode.parentNode.remove();
        }
    })
});