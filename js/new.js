jQuery.fn.myBlindToggle = function(speed, easing, callback) {
	var h = this.height() + parseInt(this.css('paddingTop')) + parseInt(this.css('paddingBottom'));
	alert("I am here.");
	return this.animate({marginTop: parseInt(this.css('marginTop')) <0 ? 0 : -h}, speed, easing, callback);  
};

jQuery.fn.slideFadeToggle = function(speed, easing, callback) {
	return this.animate({opacity: 'toggle', height: 'toggle'}, speed, easing, callback);
};

jQuery.fn.autoscroll = function(selector) {
	$('html,body').animate(
	{scrollTop: $(selector).offset().top}, 500);
}

function moveNext() { 
	// Go to next item
	var $old = $(".selected");
	if($old.length == 0 || $old.nextALL(".item").length == 0) {
		$old.removeClass("selected");
		$old = $(".item:first");
		$old.addClass("selected");
	} else {
		// This code is really bad, should find a better way
		$old.nextALL(".item:first").addClass("selected");
		$old.removeClass("selected");
	}
}

function movePrevious() {
	// Go to previous item
	var $old = $(".selected");
	if($old.length == 0 || $old.prevALL(".item").length == 0) {
		$old.removeClass("selected");
		$old = $(".item:last");
		$old.addClass("selected");
	} else {
		$old.prevALL(".item:first").addClass("selected");
		$old.removeClass("selected");
	}
}

function scrollSelected() {
	// Currently hardcoded offset, should calculate on the fly for custom CSS designs!
	$.scrollTo(".selected", "fast", {offset: -31});
}

function clickTitle() {
	$(".selected").find(".title").click();
}

function openItem(speed) {
	if(speed == null)
		speed = "fast";
	$(".selected").fadeTo(200, 1).find(".excerpt").slideDown(speed);
}

function closeItem(speed) {
	if(speed == null)
		speed = "fast";
	$(".selected").fadeTo(500, 0.60).effect("highlight", {color: "#FFF"}, 500).find(".excerpt").slideUp(speed);
}

function markRead(id) {
	$.ajax({
		type: "POST",
		url: "index.php",
		data: "op=ajax&ajax=read&id=" + id,
		dataType: "text"
	});
}

function showRead(element) {
	element.fadeTo(500, 0.60);
	element.addClass("read");
}

var biggest = 0;
var scrolled = 0;
var newest_id = 0;

function setupElements() {
/*	$(".excerpt a").click(function() {
		window.open(this.href);
		return false;
	});*/
	$(".item").scroll(function() {
		scrolled = $(this).attr("id");
		alert($(this).attr("id"));
	});
//	$(".title").click(function() {
//		$(this).parent().effect("highlight", {color: "#FFF"}, 1000).find(".excerpt").slideToggle("fast");
//		$(this).parent().find(".excerpt").toggle("blind",{ direction: "vertical" }, "fast");
//		$(this).parent().find(".excerpt").myBlindToggle("fast");
//		$(this).parent().find(".excerpt").slideFadeToggle("fast");
//		$(this).next(".excerpt").slideFadeToggle("fast");
//	});
	// Handle clicks (doesn't include middle click)	
	$(".source a").click(function() {
		window.open(this.href);
		return false;
	});
	// Handle all clicks (including middle click)
	$(".source a").mouseup(function() {
	//	window.open(this.href);
		if(!$(this).parents().find(".excerpt").is(':visible'))
			$(this).parent().parent().fadeTo(500, 0.60);
		$(".selected").removeClass("selected");
		$(this).parent().parent().addClass("selected");
		markRead($(this).parent().parent().attr("id"));
	//	return false;
	});
/*	$(".title").toggle(
		function() {
//			$(this).parent().find(".excerpt").show("blind", {direction: "vertical" }, "fast");
//			$(this).parent().find(".excerpt").show("slide", {direction: "up"}, "fast");
//			$(this).parent().fadeTo(200, 1).find(".excerpt").slideDown("fast");
			$(".selected").removeClass("selected");
			$(this).parent().addClass("selected");
			openItem();
		},
		function() {
	//		$(this).parent().find(".excerpt").hide("blind", {direction: "vertical" }, "fast");
//			$(this).parent().find(".excerpt").hide("blind", {direction: "vertical"}, 200);		
//			$(this).parent().find(".excerpt").hide("slide", {direction: "up"}, 200);
//			$(this).parent().fadeTo(500, 0.60).effect("highlight", {color: "#FFF"}, 500).find(".excerpt").slideUp("fast");
			$(".selected").removeClass("selected");
			$(this).parent().addClass("selected");
			closeItem();
		}
	);*/
	$(".title").click(function() {
		$(".selected").removeClass("selected");
		$(this).parent().addClass("selected");
		if($(this).parent().find(".excerpt").is(':visible'))
			closeItem();
		else {
			openItem();
			markRead($(this).parent().attr("id"));
		}
	});
	$("#message").click(function() {
		if(fetch) { // This will prevent clicking on the update message when lylina is already updating
			fetch = 0; // Also disables fetching
			$("#message").html("<img src=\"img/4-1.gif\" />Please wait while lylina updates...");
//			$("#throbber").show();
//			$("#throbber").fadeIn(500);
			$("#main").fadeTo(2000, 0.07);
			$("#main").load(
				"index.php",
				"op=ajax&ajax=items",
				function(responseText, textStatus, XMLHttpRequest) {
					setupElements();
					$(".read").fadeTo(0, 0.60);
					$("#main").fadeTo(500, 1);
//					$("#throbber").hide();
					$("#message").html("Get new items");
					document.title = title;
					new_items = 0;
					fetch = 1;
					if(textStatus != "success")
						alert("Update fail: " . textStatus);
				}
			);
			// Reinstall our hooks
			//setupElements();
			//$(".read").fadeTo(0, 0.60);
			//$("#main").fadeTo(500, 1);
			//$("#throbber").hide();
			//fetch = 1;
		}
	});
//	var items = $(".item"); // for speed
//	for(var i in $(".item")) {
//		alert($(".item").attr("id"));
//		if($(".item").attr("id") > newest_id) {
//			newest_id = $(".item").attr("id");
//			alert(newest_id);
//		}
//	}
	var old_newest_id = newest_id;
	$(".item").each(function() {
		if(parseInt($(this).attr("id")) > newest_id) {
			newest_id = parseInt($(this).attr("id"));
		}
		if(old_newest_id != 0 && $(this).attr("id") > old_newest_id) {
			$(this).css("background-color", "#FFFFCC");
		}
	});
}

var title = "lylina rss aggregator";

$(document).ready(function() {
	$("#message").html("<img src=\"img/4-1.gif\" />Please wait while lylina loads...");
	title = document.title;
	$(".read").fadeTo(0, 0.60);
//	$("#throbber").fadeOut(200);
	$("#main").show();
	setTimeout(fetch_feeds, 5000);

	setupElements();

	$(window).keydown(function(event) {
		switch(event.keyCode) {
			// N
			case 78:
				moveNext();
				scrollSelected();
				break;
			// J
			case 74:
				if($(".selected").find(".excerpt").is(':visible'))
					closeItem(0);
				moveNext();
				openItem(0);
				markRead($(".selected").attr("id"));
				scrollSelected();
				break;
			// P
			case 80:
				movePrevious();
				scrollSelected();
				break;
			// K
			case 75:
				if($(".selected").find(".excerpt").is(':visible'))
					closeItem();
				movePrevious();
				openItem();
				markRead($(".selected").attr("id"));
				scrollSelected();
				break;
			// O, Enter
			case 79:
			case 13:
				clickTitle();
				break;
			// V
			case 86:
				$(".selected").find(".source a").click();
				$(".selected").find(".source a").mouseup();
				break;
		}
	});
	$("#message").html("Get new items");			
});

var new_items = 0;
var fetch = 1;

function fetch_feeds() {
	if(fetch) {
		$.ajax({
			type: "POST",
			url: "index.php",
			data: "op=ajax&ajax=update&newest=" + newest_id,
			dataType: "text",
			timeout: 500 * 1000,
			success: function(msg) {
				if(fetch) {
					var old_items = new_items;
					new_items = parseInt(msg);
					if(new_items > 0) {
						$("#message").html('<b>Get new items (' + new_items + ')</b>');
						document.title = "[" + new_items + "] " + title;
						if(new_items != old_items) {
							$("#navigation").effect("highlight", {}, 2000);
						}
					}
				}
			}
		});
	}
	setTimeout(fetch_feeds, 90 * 1000);
}


