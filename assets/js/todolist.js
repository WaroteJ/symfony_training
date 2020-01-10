$(function(){
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    let uri=window.location.href;

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))  // If Internet Explorer, return version number
    {
        $(".IE").show();
        $("header").hide();
        $("main").hide();
        alert("Attention, vous utilisez Internet Explorer. Vous vous exposez à des failles de sécurité en utilisant ce navigateur");
    }

    $(".add-element").click(function(){ // Addition of a new element
        let value = $("#new-element").val();
        if (value !== ''){
            let spanAU="<div class =\"arrows\"><span class=\"arrowUp\">\u2191</span>";  //span ArrowUp + div opening
            let spanAD="<span class=\"arrowDown\">\u2193</span></div>";                 //span ArrowDown + div closing
            let spanC="<span class=\"close\">\u00D7</span>"                             //span Close

            $.ajax({
                url:uri+'/ajaxP',
                type: "POST",
                dataType: "json",
                data: {
                    'text':value
                },
                async: true,
                success: function (data)
                {
                    $("ul#myList").append("<li><input type='hidden' id='"+data.taskId+"'><input type='hidden' id='"+data.taskId+"'><p>"+value+"</p>"+spanAU+spanAD+spanC+"</li>");//Append our new element to the list
                }
            });
        }
        else alert("Champ vide");

        $("#new-element").val(''); //Empty the input field
    })
    $("#new-element").keypress(function(e){
        if(e.which == 13)    // enter key ASCII code
            $(".add-element").click();
    });


    $("#myList").click(function(e){ //Move or delete a <li> depending on the clicked area
        let item=e.target;
        if (item.tagName === "LI"){  // toggle the "checked" class on <li>
            item.classList.toggle("checked");
            let id=item.firstElementChild.id;
            let check=item.className;
            ajaxCheck(id,check);
        }
        else if (item.parentNode.tagName === "LI" && item.tagName === "P"){ // handle a click on a <p> child
            item.parentNode.classList.toggle("checked");
            let id=item.parentNode.firstElementChild.id;
            let check=item.parentNode.className;
            ajaxCheck(id,check);
        }
        switch (item.className) {
            case "close":                           // click = close button => remove the <li>
                let id = item.parentNode.firstElementChild.id;
                item.parentNode.remove();
                $.ajax({
                    url:uri+'/ajaxD',
                    type: "PUT",
                    dataType: "json",
                    data: {
                        'taskId':id
                    },
                    async: true,
                    success: function (data)
                    {

                    }
                });
                break;
            case "arrowUp":                  // click = up arrow => move up the <li>
                item.parentNode.parentNode.classList.toggle("toMove");    //We use classes to recognise our elements to move
                $(".toMove").prev("li").toggleClass("moveUp");             //inside the list
                $(".toMove").insertBefore($(".moveUp"));
                let newOrdreUp1=$(".moveUp :nth-child(2)").attr("id");
                let newOrdreUp2=$(".toMove :nth-child(2)").attr("id");
                let idUp1=$(".moveUp :nth-child(1)").attr("id");
                let idUp2=$(".toMove :nth-child(1)").attr("id");
                $(".moveUp :nth-child(2)").attr("id",newOrdreUp2);
                $(".toMove :nth-child(2)").attr("id",newOrdreUp1);
                $("li").removeClass("toMove moveUp");
                if(idUp1 && idUp2 && newOrdreUp1 && newOrdreUp2)
                    ajaxOrder(idUp1,idUp2,newOrdreUp1,newOrdreUp2);
                break;
            case "arrowDown":                // click = down arrow => move down the <li>
                item.parentNode.parentNode.classList.toggle("toMove");
                $(".toMove").next("li").toggleClass("moveDown");
                $(".toMove").insertAfter($(".moveDown"));
                let newOrdreDown1=$(".moveDown :nth-child(2)").attr("id");
                let newOrdreDown2=$(".toMove :nth-child(2)").attr("id");
                let idDown1=$(".moveDown :nth-child(1)").attr("id");
                let idDown2=$(".toMove :nth-child(1)").attr("id");
                $(".moveDown :nth-child(2)").attr("id",newOrdreDown2);
                $(".toMove :nth-child(2)").attr("id",newOrdreDown1);
                $("li").removeClass("toMove moveDown");
                if(idDown1 && idDown2 && newOrdreDown1 && newOrdreDown2)
                    ajaxOrder(idDown1,idDown2,newOrdreDown1,newOrdreDown2);
                break;
            default:
                break;
        }
    })
    
    function ajaxCheck(id, check) {
        let state;
        if (check.indexOf("checked")>=0){
            state=1;
        }
        else{
            state=0;
        }
        $.ajax({
            url:uri+'/ajaxPutChecked',
            type: "PUT",
            dataType: "json",
            data: {
                'taskId':id,
                'taskState':state
            },
            async: true,
            success: function (data)
            {

            }
        });
    }

    function ajaxOrder(firstId,secondId,firstOrder,secondOrder) {
        $.ajax({
            url:uri+'/ajaxOrderUpdate',
            type: "PUT",
            dataType: "json",
            data: {
                'firstTaskId':firstId,
                'secondTaskId':secondId,
                'firstTaskOrder':firstOrder,
                'secondTaskOrder':secondOrder
            },
            async: true,
            success: function (data)
            {

            }
        });
    }


});
import('../css/todolist.scss');
