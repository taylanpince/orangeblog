/*
*	Messages
*	A core JS library for showing user messages
*	
*	Requires jQuery library (http://www.jquery.com)
*	
*	Taylan Pince (taylanpince@gmail.com) - February 23, 2008
*/

if (typeof core == 'undefined') core = new Object();

core.messages = {
	
	show_message : function(msg, error) {
		if ($("#CoreMessageList").size() == 0) {
			$("body").append('<ul id="CoreMessageList"></ul>');
		}
		
		var id = $("#CoreMessageList > li").size();
		$("#CoreMessageList").prepend('<li id="CoreMessage' + id + '" style="display: none;">' + msg + '</li>');
		
		if ($.browser.msie && parseInt($.browser.version) < 7) {
			$("#CoreMessage" + id).each(function() {
				if ($.browser.msie && parseInt($.browser.version) < 7) {
					var bg = this.currentStyle.backgroundImage;
					var method = (this.currentStyle.backgroundRepeat == "no-repeat") ? "crop" : "scale";
					this.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod='" + method + "', src='" + bg.substring(5, bg.length - 2) + "')";
					this.style.backgroundImage = "url('" + document.media_url + "assets/images/blank.png')";
				}
			}).show();
		} else {
			$("#CoreMessage" + id).fadeIn("slow");
		}
		
		if (error == true) {
			$("#CoreMessage" + id).addClass("error");
		}
		
		setTimeout('core.messages.remove_message("' + id + '");', 8000);
	},
	
	remove_message : function(id) {
		if ($.browser.msie && parseInt($.browser.version) < 7) {
			$("#CoreMessage" + id).remove();
		} else {
			$("#CoreMessage" + id).fadeOut("slow", function() {
				$(this).remove();
			});
		}
	},
	
	init : function() {
		if ($("#Messages").size() > 0) {
			$("#Messages > li").each(function() {
				core.messages.show_message($(this).html(), false);
			});
			$("#Messages").remove();
		}
	}
	
};

$(function() {
	core.messages.init();
});