/*
*	orangeblog
*	Contains utility / interface functions for orangeblog
*	
*	Requires jQuery library (http://www.jquery.com)
*	
*	Taylan Pince (taylanpince@gmail.com) - February 23, 2007
*/

orangeBlog = {
		
	mark_lists : function() {
		$("li:last-child").addClass("last-child");
		$("li:first-child").addClass("first-child");
	},
	
	mark_forms : function() {
		$("input[@type=text]").addClass("text");
		$("input[@type=password]").addClass("text");
		$("input[@type=file]").addClass("file");
		$("input[@type=radio]").addClass("radio");
		$("input[@type=checkbox]").addClass("checkbox");
		$("input[@type=image]").addClass("image");
		$("input[@type=submit]").addClass("submit");
	},
	
	mark_separators : function() {
		$("hr").replaceWith('<div class="hr">* * *</div>');
	},
	
	init_title_form : function() {
		if ($("#PostTitleForm").size() > 0) {
			$("#PostTitleForm").submit(function() {
				if ($(this).find("#id_PostTitleForm-title").val() == "") {
					$(this).find("#id_PostTitleForm-title").focus();
					core.messages.show_message("başlık girsene önce evladım...", true);
					return false;
				} else {
					return true;
				}
			});
		}
	},
	
	init : function() {
		this.mark_lists();
		this.mark_forms();
		this.mark_separators();
		this.init_title_form();
	}
	
};

$(function() {
	orangeBlog.init();
});
