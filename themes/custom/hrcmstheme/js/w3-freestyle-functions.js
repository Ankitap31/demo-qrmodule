/**********************************************************
 ******* Component Related Content Below This Line  *******
 **********************************************************/
/************************************************/
/*  This Section Controls The Country Selector  */
/************************************************/
jQuery('#country-selector').click(function(e){
	e.preventDefault();
	jQuery('#country-modal').modal({
		backdrop: 'true', //Options: true|false|static
		keyboard: true //Options: true|false
	});
});
/*************************************************/
/* This Functions Submits The Country Selction	 */
/*************************************************/
function submitFormavalues(formid){
//	document.getElementById(formid.name).submit();
}
/*************************************************/
/* This Section Controls The Search Box Function */
/*************************************************/
try{
	jQuery('.search-box form').attr('action','https://myitsupport.ext.hpe.com/myITsupport/HRArticleSearch');
}catch(err){;}
/******************************************/
/* This Section Controls The Slick Slider */
/******************************************/
/* function slickInit(){
	if(jQuery('body').has('[class^=slick-container-],[class*= slick-container-]')){
		jQuery('[class^=slick-container-],[class*= slick-container-]').not('.w3-modal [class^=slick-container-], .w3-modal [class*= slick-container-]').each(function(){
			jQuery(this).slick({
				speed: 500,
				prevArrow: '<div class="w3-left-arrow w3-text-grey w3-hover-text-green w3-jumbo">&lsaquo;</div>',
				nextArrow: '<div class="w3-right-arrow w3-text-grey w3-hover-text-green w3-jumbo">&rsaquo;</div>',
			});
		});
	}		
} */
/*********************************************/
/* This Section Controls The Image Component */
/*********************************************/
var slideIndex;
var previousDot = 1;
jQuery(document).ready(function(){	
	if(jQuery('body').find('.image-container').length > 0){
		slideIndex = 1;
		showDivs(slideIndex);
	}
	var timer = window.setInterval(function(){console.log('auto:play');plusDivsR(1);},5000);
	jQuery('.image-container').hover(function(){console.log('hover:pause');clearInterval(timer);},function(){console.log('resume:play'); timer = window.setInterval(function(){plusDivsR(1);},5000)});
});

function currentDiv(n){
	if(n < previousDot)
		plusDivsL(-1);
	else if(n > previousDot)
		plusDivsR(1);
	previousDot = n;
}
function plusDivsL(n) {
	jQuery('.image-container .image-slides.active').animate({left: '-100%',opacity:'0'},'slow',function(){
		activeReset();
		showDivs(slideIndex += n);
		jQuery(this).removeClass('active');
	});
}
function plusDivsR(n) {
	jQuery('.image-container .image-slides.active').animate({right: '-100%',opacity:'0'},'slow',function(){
		activeReset();
		showDivs(slideIndex += n);
		jQuery(this).removeClass('active');
	});
}
function activeReset(){
	jQuery('.active').removeAttr('style');
}
function showDivs(n) {
	try{
		var i;
		var x = jQuery('.image-container .image-slides');
		var dots = document.getElementsByClassName('image-component-dot');
		if (n > x.length) {slideIndex = 1}    
		if (n < 1) {slideIndex = x.length}
		jQuery('.image-component-dot.w3-dark-gray').addClass('w3-transparent');
		jQuery('.image-component-dot').removeClass('w3-dark-gray');
		x[slideIndex-1].className += ' active';
		dots[slideIndex-1].className += ' w3-dark-gray';
		jQuery('.image-component-dot.w3-dark-gray').removeClass('w3-transparent');
	}catch(err){console.log(err); }
}
function pauseVid(){
	var vidID = document.getElementsByClassName("w3-modal")[0].getElementsByTagName("VIDEO")[0].id;
	document.getElementById(vidID).pause();
}
/*************************************************/
/* This Section Controls The Modal Functionality */
/*************************************************/		
function modal_open(e){
	var modal = e.currentTarget.attributes.name.nodeValue;
	if(jQuery('.w3-modal').hasClass('w3-show'))
		modal_close(e);
	url_var_append(e);
	//alert( modal);
	jQuery('#'+modal).addClass('w3-show');
	if(jQuery('#'+modal).has('[class^=slick-container-],[class*= slick-container-]'))
		slickModal(modal);
}
function modal_close(e){
	url_var_remove(e);
	jQuery('.w3-modal.w3-show').removeClass('w3-show');
	if(jQuery('.w3-modal').has('[class^=slick-container-],[class*= slick-container-]'))
		removeSlickModal();
}
/*** Modal Sliders ***/
function slickModal(modal){
	jQuery('#'+modal+' [class^=slick-container-],'+'#'+modal+' [class*= slick-container-]').slick({
		speed: 500,
		prevArrow: '<div class="w3-left-arrow w3-text-grey w3-hover-text-green w3-jumbo">&lsaquo;</div>',
		nextArrow: '<div class="w3-right-arrow w3-text-grey w3-hover-text-green w3-jumbo">&rsaquo;</div>',
	});
}
function slickModalDirectLink(){
	var slider = jQuery('.w3-modal.w3-show [class^=slick-container-],.w3-modal.w3-show [class*="slick-container-"]').attr('name');
	var i = location.hash;
	i = i.replace('#','');
	if(location.hash)
		slick_goTo(slider,i);
}
function slick_goTo(slider,i){
	try{
		jQuery('.'+slider).slick('slickGoTo',i,false);
	}catch(error){;}
	current_slide(i);
}
function current_slide(i){
	location.hash = i;
}
function removeSlickModal(){
	try{
		jQuery('.w3-modal [class^=slick-container-],.w3-modal [class*= slick-container-]').slick('unslick');
	}catch(e){}
}
/*** Modal Tabs ***/
function tab_open(e){
	var tab;
	tab_close(e);
	if(location.hash)
		tab = location.hash.replace('#','');
	else
		tab = e.currentTarget.attributes.name.nodeValue;
	tabModalHash(tab);
	jQuery('[name='+tab+'] div').addClass('w3-border-green');
	jQuery('#'+tab).addClass('w3-show');
}
function tab_close(e){
	if(typeof(e) != 'string')
		location.hash = '';
	jQuery('.w3-tab-container .w3-border-green').removeClass('w3-border-green');
	jQuery('.w3-tab-container .w3-show').removeClass('w3-show');
}
function tabModalHash(e){
	return location.hash = e;
}
function tabModalDirectLink(){
	var i = location.hash;
	if(i){
		i = i.replace('#','')
		tab_open(i);
	}
}
/*** Modal Toggles ***/
function answer(e)
{
	var x = jQuery('#'+e.currentTarget.attributes.name.nodeValue);
	if(x[0].className.indexOf('w3-show-answer') < 0 )
	{
		jQuery('#'+x[0].id).slideDown('fast');	x.addClass('w3-show-answer');
	}else{
		jQuery('#'+x[0].id).slideUp('fast');x.removeClass('w3-show-answer');
	}
}
function url_var_append(e){
	window.history.pushState({},'','?'+e.currentTarget.attributes.name.nodeValue);
}
function url_var_remove(e){
	window.history.replaceState({},'',location.pathname);
}
/******************************************************/
/* This Section Controls The Image Zoom Functionality */
/******************************************************/
function image_zoom(e){
	if(e.currentTarget.attributes.class.value.indexOf('w3-zoom') < 0){
		e.currentTarget.attributes.class.value+=' w3-zoom';
	}
	else{
		jQuery('.w3-zoom').removeClass('w3-zoom');
	}
}
/*************************************************/
/* This Section Controls Accordian Functionality */
/*************************************************/
function accordian_controller(e){
	var o = e.currentTarget.attributes;
	if(o.class.nodeValue.indexOf('w3-open') <= 0){
		accordian_open(o);
	}
	else{
		accordian_close(o);
	}
}
function accordian_open(o){
	jQuery('[name="'+o.name.value+'"]').addClass('w3-open');
	jQuery('[name="'+o.name.value+'"] .w3-close').addClass('w3-show');
	jQuery('#'+o.name.value).addClass('w3-show');
}
function accordian_close(o){
	jQuery('[name="'+o.name.value+'"] .w3-close').removeClass('w3-show');
	jQuery('#'+o.name.value).removeClass('w3-show');
	jQuery('[name="'+o.name.value+'"]').removeClass('w3-open');
}
