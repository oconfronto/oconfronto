$(function () {
  var note = $("#note"),
    ts = new Date("August 17, 2012 14:00:00").getTime();

  $("#countdown").countdown({
    timestamp: ts,
    callback: function (days, hours, minutes, seconds) {
      var message = "";

      message += days + " dia" + (days == 1 ? "" : "s") + ", ";
      message += hours + " hora" + (hours == 1 ? "" : "s") + ", ";
      message += minutes + " minuto" + (minutes == 1 ? "" : "s") + " e ";
      message += seconds + " segundo" + (seconds == 1 ? "" : "s") + " <br />";

      note.html(message);
    },
  });
});
