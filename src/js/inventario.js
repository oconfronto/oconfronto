	$(document).ready(function(){
		$("#gildie_amuletos").hide();
	   		$("#gildie_armas").hide();
	   		$("#gildie_armaduras").hide();
	   		$("#gildie_botas").hide();
	   		$("#gildie_calças").hide();
	   		$("#gildie_elmos").hide();
			$("#gildie_escudos").hide();
			$("#gildie_especiais").hide();
			$("#news1").hide();
			$("#news2").hide();
			$("#news3").hide();
			$("#news4").hide();
			$("#news5").hide();
	   		$("#gildie_amuletos tr:even").css("background-color", "");
	   		$("#gildie_armas tr:even").css("background-color", "");
	   		$("#gildie_armaduras tr:even").css("background-color", "");
	   		$("#gildie_botas tr:even").css("background-color", "");
	   		$("#gildie_calças tr:even").css("background-color", "");
	   		$("#gildie_elmos tr:even").css("background-color", "");
			$("#gildie_escudos tr:even").css("background-color", "");
			$("#gildie_especiais tr:even").css("background-color", "");
			$("#news1 tr:even").css("background-color", "");
			$("#news2 tr:even").css("background-color", "");
			$("#news3 tr:even").css("background-color", "");
			$("#news4 tr:even").css("background-color", "");
			$("#news5 tr:even").css("background-color", "");
	   			
  		});
  			var amuletos = 0;
	   		var armas = 0;
	   		var armaduras = 0;
	   		var botas = 0;
	   		var calças = 0;
	   		var elmos = 0;
			var escudos = 0;
			var especiais = 0;
			var news1 = 0;
			var news2 = 0;
			var news3 = 0;
			var news4 = 0;
			var news5 = 0;
	   		
			function ex_amuletos() {
	  			if(amuletos%2==0) {
	  				$("#gildie_amuletos").slideDown("slow");
	  			} else {
	  				$("#gildie_amuletos").slideUp("slow");
	  			}
	  			amuletos = amuletos+1;
	  		}
			
			function ex_armas() {
	  			if(armas%2==0) {
	  				$("#gildie_armas").slideDown("slow");
	  			} else {
	  				$("#gildie_armas").slideUp("slow");
	  			}
	  			armas = armas+1;
	  		}
			
			function ex_armaduras() {
	  			if(armaduras%2==0) {
	  				$("#gildie_armaduras").slideDown("slow");
	  			} else {
	  				$("#gildie_armaduras").slideUp("slow");
	  			}
	  			armaduras = armaduras+1;
	  		}
			
			function ex_botas() {
	  			if(botas%2==0) {
	  				$("#gildie_botas").slideDown("slow");
	  			} else {
	  				$("#gildie_botas").slideUp("slow");
	  			}
	  			botas = botas+1;
	  		}
			
			function ex_calças() {
	  			if(calças%2==0) {
	  				$("#gildie_calças").slideDown("slow");
	  			} else {
	  				$("#gildie_calças").slideUp("slow");
	  			}
	  			calças = calças+1;
	  		}
			
			function ex_elmos() {
	  			if(elmos%2==0) {
	  				$("#gildie_elmos").slideDown("slow");
	  			} else {
	  				$("#gildie_elmos").slideUp("slow");
	  			}
	  			elmos = elmos+1;
	  		}
			function ex_escudos() {
	  			if(escudos%2==0) {
	  				$("#gildie_escudos").slideDown("slow");
	  			} else {
	  				$("#gildie_escudos").slideUp("slow");
	  			}
	  			escudos = escudos+1;
	  		}
			function ex_especiais() {
	  			if(especiais%2==0) {
	  				$("#gildie_especiais").slideDown("slow");
	  			} else {
	  				$("#gildie_especiais").slideUp("slow");
	  			}
	  			especiais = especiais+1;
	  		}
			function ex_news1() {
	  			if(news1%2==0) {
	  				$("#news1").slideDown("slow");
	  			} else {
	  				$("#news1").slideUp("slow");
	  			}
	  			news1 = news1+1;
	  		}
			function ex_news2() {
	  			if(news2%2==0) {
	  				$("#news2").slideDown("slow");
	  			} else {
	  				$("#news2").slideUp("slow");
	  			}
	  			news2 = news2+1;
	  		}
			function ex_news3() {
	  			if(news3%2==0) {
	  				$("#news3").slideDown("slow");
	  			} else {
	  				$("#news3").slideUp("slow");
	  			}
	  			news3 = news3+1;
	  		}
			function ex_news4() {
	  			if(news4%2==0) {
	  				$("#news4").slideDown("slow");
	  			} else {
	  				$("#news4").slideUp("slow");
	  			}
	  			news4 = news4+1;
	  		}
			function ex_news5() {
	  			if(news5%2==0) {
	  				$("#news5").slideDown("slow");
	  			} else {
	  				$("#news5").slideUp("slow");
	  			}
	  			news5 = news5+1;
	  		}
		