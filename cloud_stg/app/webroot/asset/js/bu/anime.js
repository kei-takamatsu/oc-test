$(function(){
$('.fade').css('visibility','hidden');
$('.fade2').css('visibility','hidden');
$('.fade3').css('visibility','hidden');
$('.fade4').css('visibility','hidden');
$('.fade_left').css('visibility','hidden');
$('.fade_right').css('visibility','hidden');
$('#animation').css('visibility','hidden');
$(window).scroll(function(){
 var testWindowHeight = $(window).height(),
     testTopWindow = $(window).scrollTop(),
	 targetPosition;
 $('.fade').each(function(){
  targetPosition = $(this).offset().top;
  if(testTopWindow > targetPosition - testWindowHeight + 100){
   $(this).addClass("fadeInDown");
  }
 });
 $('.fade2').each(function(){
  targetPosition = $(this).offset().top;
  if(testTopWindow > targetPosition - testWindowHeight + 100){
   $(this).addClass("fadeInDown2");
  }
  });
   $('.fade3').each(function(){
  targetPosition = $(this).offset().top;
  if(testTopWindow > targetPosition - testWindowHeight + 100){
   $(this).addClass("fadeInDown3");
  }
  });
   $('.fade4').each(function(){
  targetPosition = $(this).offset().top;
  if(testTopWindow > targetPosition - testWindowHeight + 100){
   $(this).addClass("fadeInDown4");
  }
 });
    $('.fade_left').each(function(){
  targetPosition = $(this).offset().top;
  if(testTopWindow > targetPosition - testWindowHeight + 200){
   $(this).addClass("fadeInLeft");
  }
 });
 $('.fade_right').each(function(){
  targetPosition = $(this).offset().top;
  if(testTopWindow > targetPosition - testWindowHeight + 200){
   $(this).addClass("fadeInRight");
  }
 });
});});
