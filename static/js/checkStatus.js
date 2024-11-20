testee = function (stat_points) {
  if (stat_points <= 0) {
    $("input[id^='buttonAdd']").attr("disabled", true);
    $("#pontos")
      .html("Você não possui mais pontos de status disponíveis.")
      .css("background-color", "#FFFDE0");
  }

  $("input[id^='buttonAdd']").click(function () {
    var id = parseInt(this.id.replace("buttonAdd", ""));
    if (stat_points > 0) {
      $("#checkStatus" + id).val(Number($("#checkStatus" + id).val()) + 1);
    }
    $("#checkStatus" + id).keyup();
  });

  $("input[id^='buttonSub']").click(function () {
    var id = parseInt(this.id.replace("buttonSub", ""));
    if (Number($("#checkStatus" + id).val()) > 0) {
      $("#checkStatus" + id).val(Number($("#checkStatus" + id).val()) - 1);
      $("#checkStatus" + id).keyup();
    }
  });

  $("input[id^='checkStatus']").keyup(function (event) {
    if ($(this).val().length > 1) {
      $(this).val(this.value.replace(/^[ 0]/g, ""));
    }
    $(this).val(this.value.replace(/[^0-9]/g, ""));

    if ($(this).val() == "0" || $(this).val() == "") {
      var id = parseInt(this.id.replace("checkStatus", ""));
      $("#buttonSub" + id).attr("disabled", true);
    } else {
      var id = parseInt(this.id.replace("checkStatus", ""));
      $("#buttonSub" + id).attr("disabled", false);
    }

    if (stat_points > 0) {
      var usados =
        Number($("#checkStatus1").val()) +
        Number($("#checkStatus2").val()) +
        Number($("#checkStatus3").val()) +
        Number($("#checkStatus4").val());

      if (stat_points - usados >= 0) {
        $("#pontos")
          .html(
            "Você tem " +
              (stat_points - usados) +
              " ponto de status disponíveis."
          )
          .css("background-color", "#45E61D");
        if (stat_points - usados == 0) {
          $("#pontos")
            .html(
              "Você tem " +
                (stat_points - usados) +
                " ponto de status disponíveis."
            )
            .css("background-color", "#FFFDE0");
          $("input[id^='buttonAdd']").attr("disabled", true);
        } else {
          $("input[id^='buttonAdd']").attr("disabled", false);
        }
      } else {
        $("input[id^='buttonAdd']").attr("disabled", true);
        $("#pontos")
          .html(
            'Pontos insuficientes.<br/><font size="1px">Usando ' +
              usados +
              " de " +
              stat_points +
              " pontos disponíveis."
          )
          .css("background-color", "#EEA2A2");
      }
    }
  });
};
