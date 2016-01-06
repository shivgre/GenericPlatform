<?php

function test($name){
    
    ?>

<h1><?= $name;?></h1>
<?php
}
?>


<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<title>Example of Bootstrap 3 Modals</title>
<link rel='stylesheet' href='http://localhost/generic-platforms/application/css/bootstrap.min_1.css' type='text/css'>
   <link rel="stylesheet" href="http://localhost/generic-platforms/application/css/style.css" type="text/css">

<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
 
<style>
#page_navigation{display: block;clear: both;}    
#page_navigation a{
    
	padding:3px;
	border:1px solid gray;
	margin:2px;
	color:black;
	text-decoration:none
}
.active_page{
	background:darkblue;
	color:white !important;
}
</style>



<script type="text/javascript">
$(document).ready(function(){
	
	//how much items per page to show
	var show_per_page = 2; 
	//getting the amount of elements inside content div
	var number_of_items = $('#content').children('.boxView').size();
	//calculate the number of pages we are going to have
	var number_of_pages = Math.ceil(number_of_items/show_per_page);
	
	//set the value of our hidden input fields
	$('#current_page').val(0);
	$('#show_per_page').val(show_per_page);
	
	//now when we got all we need for the navigation let's make it '
	
	/* 
	what are we going to have in the navigation?
		- link to previous page
		- links to specific pages
		- link to next page
	*/
	var navigation_html = '<a class="previous_link" href="javascript:previous();">Prev</a>';
	var current_link = 0;
	while(number_of_pages > current_link){
		navigation_html += '<a class="page_link" href="javascript:go_to_page(' + current_link +')" longdesc="' + current_link +'">'+ (current_link + 1) +'</a>';
		current_link++;
	}
	navigation_html += '<a class="next_link" href="javascript:next();">Next</a>';
	
	$('#page_navigation').html(navigation_html);
	
	//add active_page class to the first page link
	$('#page_navigation .page_link:first').addClass('active_page');
	
	//hide all the elements inside content div
	$('#content').children('.boxView').css('display', 'none');
	
	//and show the first n (show_per_page) elements
	$('#content').children('.boxView').slice(0, show_per_page).css('display', 'block');
	
});

function previous(){
	
	new_page = parseInt($('#current_page').val()) - 1;
	//if there is an item before the current active link run the function
	if($('.active_page').prev('.page_link').length==true){
		go_to_page(new_page);
	}
	
}

function next(){
	new_page = parseInt($('#current_page').val()) + 1;
	//if there is an item after the current active link run the function
	if($('.active_page').next('.page_link').length==true){
		go_to_page(new_page);
	}
	
}
function go_to_page(page_num){
	//get the number of items shown per page
	var show_per_page = parseInt($('#show_per_page').val());
	
	//get the element number where to start the slice from
	start_from = page_num * show_per_page;
	
	//get the element number where to end the slice
	end_on = start_from + show_per_page;
	
	//hide all children elements of content div, get specific items and show them
	$('#content').children('.boxView').css('display', 'none').slice(start_from, end_on).css('display', 'block');
	
	/*get the page link that has longdesc attribute of the current page and add active_page class to it
	and remove that class from previously active page link*/
	$('.page_link[longdesc=' + page_num +']').addClass('active_page').siblings('.active_page').removeClass('active_page');
	
	//update the current page input field
	$('#current_page').val(page_num);
}
  
</script>

 

</head>
<body>
    
    <!-- the input fields that will hold the variables we will use -->
	<input type='hidden' id='current_page' />
	<input type='hidden' id='show_per_page' />
	
	<!-- Content div. The child elements will be used for paginating(they don't have to be all the same,
		you can use divs, paragraphs, spans, or whatever you like mixed together). '-->
        <div id='content'>
            
            
            
            
<div class="project-details-wrapper boxView">
                            <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
                                <div class="project-detail"> 

                                    <a href=""><span class="profile-image">
        <img src="http://localhost/generic-platforms/users_uploads/NO-IMAGE-AVAILABLE-ICON.jpg" alt="" class="img-responsive"></span></a><span class="list-data"><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false"><span class="list-span">solid  1 </span></a><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false" class="btn btn-primary edit">Edit</a></span></div></div></div>
        
        
        <div class="project-details-wrapper boxView">
                            <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
                                <div class="project-detail"> 

                                    <a href=""><span class="profile-image">
        <img src="http://localhost/generic-platforms/users_uploads/377Chrysanthemum.jpg" alt="" class="img-responsive"></span></a><span class="list-data"><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false"><span class="list-span">2   </span></a><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false" class="btn btn-primary edit">Edit</a></span></div></div></div>
        
        
        
        <div class="project-details-wrapper boxView">
                            <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
                                <div class="project-detail"> 

                                    <a href=""><span class="profile-image">
        <img src="http://localhost/generic-platforms/users_uploads/650Tulips.jpg" alt="" class="img-responsive"></span></a><span class="list-data"><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false"><span class="list-span">3   </span></a><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false" class="btn btn-primary edit">Edit</a></span></div></div></div>
        
        
        <div class="project-details-wrapper boxView">
                            <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
                                <div class="project-detail"> 

                                    <a href=""><span class="profile-image">
        <img src="http://localhost/generic-platforms/users_uploads/NO-IMAGE-AVAILABLE-ICON.jpg" alt="" class="img-responsive"></span></a><span class="list-data"><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false"><span class="list-span">4   </span></a><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false" class="btn btn-primary edit">Edit</a></span></div></div></div>
        
        
        <div class="project-details-wrapper boxView">
                            <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
                                <div class="project-detail"> 

                                    <a href=""><span class="profile-image">
        <img src="http://localhost/generic-platforms/users_uploads/NO-IMAGE-AVAILABLE-ICON.jpg" alt="" class="img-responsive"></span></a><span class="list-data"><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false"><span class="list-span">5   </span></a><a href="http://localhost/generic-platforms/system/profile.php?display=myaccount&amp;tab=transactions&amp;tabNum=6&amp;ta=transactions&amp;search_id=108&amp;checkFlag=true&amp;table_type=child&amp;edit=true#false" class="btn btn-primary edit">Edit</a></span></div></div></div>
        
        
        
        </div>
        
        
        <br>
	<!-- An empty div which will be populated using jQuery -->
	<div id='page_navigation'></div>
    
    <?php
  
    echo test("khan");
?>    
</body>
</html>                                		