$(document).ready(function() {
    
    totalMenus = 2;
    for (i = 1; i <= totalMenus; i++) {
        if ($.cookie("tab"+i) != null) {
            $("#gaita"+i).attr("style", "display:none");
            if (i == totalMenus) {
                $("#mudar"+i).html("<img src=\"images/menu/off"+i+".png\" style=\"-webkit-border-bottom-right-radius:5px; -moz-border-bottom-right-radius:5px; border-bottom-right-radius:5px; -webkit-border-bottom-left-radius:5px; -moz-border-bottom-left-radius:5px; border-bottom-left-radius:5px;\" border=\"0px\">");
            } else {
                $("#mudar"+i).html("<img src=\"images/menu/off"+i+".png\" border=\"0px\">");
            }
        }
    }
    
    $("[id^='mudar']").click(function(){
                                  var id = parseInt(this.id.replace("mudar", ""));
                                  
                                  if (($.cookie("tab"+id) != null) || ($.cookie("tab"+id) == 1)) {
                                    $.cookie("tab"+id, null);
                                    $.cookie("tab"+id, 5, { path: '/', expires: -1 });
                                    $("#gaita"+id).attr("style", "");
                                    $("#mudar"+id).html("<img src=\"images/menu/on"+id+".png\" border=\"0px\">");
                                  } else {
                                    $.cookie("tab"+id, 1, { path: '/', expires: 30 });
                                    $("#gaita"+id).attr("style", "display:none");
                                    if (id == totalMenus) {
                                       $("#mudar"+id).html("<img src=\"images/menu/off"+id+".png\" style=\"-webkit-border-bottom-right-radius:5px; -moz-border-bottom-right-radius:5px; border-bottom-right-radius:5px; -webkit-border-bottom-left-radius:5px; -moz-border-bottom-left-radius:5px; border-bottom-left-radius:5px;\" border=\"0px\">");
                                    } else {
                                       $("#mudar"+id).html("<img src=\"images/menu/off"+id+".png\" border=\"0px\">");
                                    }
                                  }
                                  });
    
});