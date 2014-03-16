var EditMeal = {
	init: function(selector, data){

		$(selector).empty();
		$(selector).off("click");

		$(selector).append($("#add-course-template").clone().removeAttr("id").removeClass("hide"));

		if(arguments.length==2){
			EditMeal.setMealData(selector, data);
		}

		$(selector).on("click", ".add-option", function(e){
			e.preventDefault();
			EditMeal.addOption($(this).closest(".course-options"));
		});

		$(selector).on("click", ".add-course", function(e){
			e.preventDefault();
			EditMeal.addCourse(selector);
		});

		$(selector).on("click", ".course-move-down", function(e){
			e.preventDefault();
			var $course = $(this).closest(".course-info");
			var $options = $course.next();

			var $after = $options.next().next();
			if($after.length == 1){
				$after.after($course.detach(), $options.detach());
			}
			EditMeal.renumberCourses(selector);
		});
		$(selector).on("click", ".course-move-up", function(e){
			e.preventDefault();
			var $course = $(this).closest(".course-info");
			var $options = $course.next();

			var $before = $course.prev().prev();
			if($before.length == 1){
				$before.before($course.detach(), $options.detach());
			}
			EditMeal.renumberCourses(selector);
		});
		$(selector).on("click", ".course-remove", function(e){
			e.preventDefault();
			var $course = $(this).closest(".course-info");
			var $options = $course.next();

			$course.remove();
			$options.remove();
			EditMeal.renumberCourses(selector);
		});

		$(selector).on("click", ".option-move-down", function(e){
			e.preventDefault();
			var $option = $(this).closest(".option-info");
			var $after = $option.next(".option-info");
			if($after.length == 1){
				$after.after($option.detach());
			}
			EditMeal.renumberOptions($(this).closest(".course-options"));
		});
		$(selector).on("click", ".option-move-up", function(e){
			e.preventDefault();
			var $option = $(this).closest(".option-info");
			var $before = $option.prev();
			if($before.length == 1){
				$before.before($option.detach());
			}
			EditMeal.renumberOptions($(this).closest(".course-options"));
		});
		$(selector).on("click", ".option-remove", function(e){
			e.preventDefault();
			$(this).closest(".option-info").remove();
			EditMeal.renumberOptions($(this).closest(".course-options"));
		});

	},

	renumberCourses: function(selector){
		$(selector+" .course-number").each(function(i){
			$(this).text(i+1);
		});
	},

	renumberOptions: function(container){
		container.find(".option-number").each(function(i){
			$(this).text(i+1);
		});
	},

	addCourse: function(selector, data){
		if(arguments.length == 1){
			data = {title:'', options:[]}
		}
		var $course = $("#course-template").clone();
		$course.find(".course-title").val(data.title);
		for(var i=0; i<data.options.length; i++){
			EditMeal.addOption($course.find(".course-options"), data.options[i]);
		}
		if(data.options.length == 0){
			EditMeal.addOption($course.find(".course-options"));
		}
		$(selector+" .add-course-container").before($course.children());
		EditMeal.renumberCourses(selector);
	},

	addOption: function(appendTo, data){
		if(arguments.length == 1){
			data = {name:'', chef:'', description:''}
		}
		var $option = $("#option-template").clone();
		$option.find(".option-name").val(data.name);
		$option.find(".option-chef").val(data.chef);
		$option.find(".option-description").val(data.description);
		appendTo.children().last().before($option.children());
		EditMeal.renumberOptions(appendTo);
	},

	getMealData: function(selector){
		var data = [];
		$(selector+" .course-info").each(function(i){
			var course = {};
			course.title = $(this).find(".course-title").val();
			course.options = [];
			$(this).next().find(".course-options").children().not(":last").each(function(j){
				course.options[j] = {
					name: $(this).find(".option-name").val(),
					chef: $(this).find(".option-chef").val(),
					description: $(this).find(".option-description").val()
				}
			});
			data[i] = course;
		});
		return data;
	},

	setMealData: function(selector, meal_data){
		for(var i=0; i<meal_data.length; i++){
			EditMeal.addCourse(selector, meal_data[i]);
		}
	}
}
