$(document).ready(function(){


});

function completeBill(bill_id,group_id,user_id) {
	$.post( 'billpaid.php' , { bid : bill_id , gid : group_id , uid : user_id }, 
		function( response ) {
		
			var elem = document.getElementById('bill-id-' + bill_id);
			fadeOut(elem);
			location.reload(true);
		}
	);
}

function fadeOut(el){
  el.style.opacity = 1;

  (function fade() {
    if ((el.style.opacity -= .1) < 0) {
      el.style.display = "none";
    } else {
      requestAnimationFrame(fade);
    }
  })();
}