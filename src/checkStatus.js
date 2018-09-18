$(function(){
    $("input[id^='buttonAdd']").click(function() {
        var id = parseInt(this.id.replace("buttonAdd", ""));
        $("#checkStatus"+id).val(Number($("#checkStatus"+id).val()) + 1);
        $("#checkStatus"+id).keyup();
    });    

    $("input[id^='buttonSub']").click(function() {
        var id = parseInt(this.id.replace("buttonSub", ""));
        if (Number($("#checkStatus"+id).val()) > 0) {
            $("#checkStatus"+id).val(Number($("#checkStatus"+id).val()) - 1);
            $("#checkStatus"+id).keyup();
        }
    });    


    $("input[id^='checkStatus']").keyup(function (event) {
        var stat_points = <?=$player->stat_points?>;
        $(this).val(this.value.replace(/[^0-9]/g,''));

        if($(this).val() == "0") {
            var id = parseInt(this.id.replace("checkStatus", ""));
            $("#buttonSub"+id).attr("disabled", true);
        }            

        var usados = Number($("#checkStatus1").val()) + Number($("#checkStatus2").val())+ Number($("#checkStatus3").val()) + Number($("#checkStatus4").val());

        if ((stat_points - usados) >= 0) {      
            $("#pontos").html("Você tem " + (stat_points - usados) + " ponto(s) de status restantes.").css("background","");
            if ((stat_points - usados) == 0) {
                $("input[id^='buttonAdd']").attr("disabled", true);  
            } else {
                $("input[id^='buttonAdd']").attr("disabled", false);  
            }
        } else {
            $("input[id^='buttonAdd']").attr("disabled", true);
            $("#pontos").html("Pontos insuficientes.<br/><font size=\"1px\">Usando " + usados + " de " + stat_points + " pontos disponíveis.").css("background","red");
        }
    });
});​