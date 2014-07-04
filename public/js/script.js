$(document).ready(function(){
	//hide menu divs
	$('.full-concept').hide();
	$('.exp').hide();
	$('.gran-vis').hide();
	$('.contact-cont').hide()
	
	
	//general close button
	$('.gen-x-btn').click(function(){
		$(this).parent().slideUp('slow');
	})
	
	
	//full-concept-menu toggle
	$('.full-c').click(function(e){
		e.preventDefault();
		$('.full-concept').slideToggle('slow');
		$('.full-concept').siblings().hide();
		
	});
	
	
	//exp-menu toggle
	$('.experiencia').click(function(e){
		e.preventDefault();
		$('.exp').slideToggle('slow');
		$('.exp').siblings().hide();
	});


	//vision-menu toggle
	$('.vision').click(function(e){
		e.preventDefault();
		$('.gran-vis').slideToggle('slow');
		$('.gran-vis').siblings().hide();
	});
	
	
	//contacto-menu toggle
	$('.contacto').click(function(e){
		e.preventDefault();
		$('.contact-cont').slideToggle('slow');
		$('.contact-cont').siblings().hide();
	});
	
	
});