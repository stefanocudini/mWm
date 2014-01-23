$(function() {
	
	var geoip$ = $('#geoipbox');//.hide();

	function formatJSON(json) {
		var html='<a class="aclose" href="#">&times;</a>';
		for(k in json)
			html += '<em>'+k+': </em><b>'+json[k]+'</b><br>';
		return html;
	}
	
	function showIp(ip) {
		$.getJSON(window.location.href, {geoip: ip }, function(json) {
			geoip$.html(formatJSON(json)).show();
		});
	}	
	
	$('#geoipform').on('submit', function(e) {
		showIp( $(this).find(':text').val() );
		return false;
	});
	
	geoip$.on('click', '.aclose', function(e) {
		e.preventDefault();
		$(this).parent().hide();
	});
	
	$('.ip').on('click', function(e) {
		showIp( $(this).text() );
		return false;
	});
	
	$(window)
	.on('resize',function(e) {
		$('#bottom').css({marginTop: $('#top_wrap').height()+16});
	})
	.on('hashchange load',function(e) {
	
		var hash = window.location.href.split("#")[1];
	
		if(hash=='down')
			$('#top').slideDown('fast', function() {
				$('#topup').attr({'href':'#up','class':'up'}).trigger('resize');
			});
		else if(hash=='up')
			$('#top').slideUp('fast', function() {
				$('#topup').attr({'href':'#down','class':'down'}).trigger('resize');
			});			
	});

});