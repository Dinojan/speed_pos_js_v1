$('body').css('scrollbar-width', 'thin');

var deviceType = (window.innerWidth <= 768) ? 'mobile' : 'desktop';  // or other logic

var storeApp = (function ($) {
	"use strict";
	return {
		codeEditor: function () {
			if ($("#template-content-editor").length) {
				window.editAreaLoader.init({
					id: "template-content-editor"	// id of the textarea to transform		
					, font_size: "10"
					, allow_resize: "no"
					, allow_toggle: true
					, language: "en"
					, syntax: "html"
					, toolbar: "save, |, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, help"
					, save_callback: "save_template_content_data"
					, replace_tab_by_spaces: 4
					, min_height: 550
				});

				window.editAreaLoader.init({
					id: "template-css-editor"	// id of the textarea to transform		
					, font_size: "10"
					, allow_resize: "no"
					, allow_toggle: true
					, language: "en"
					, syntax: "css"
					, toolbar: "save, |, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, help"
					, save_callback: "save_template_css_data"
					, replace_tab_by_spaces: 4
					, min_height: 550
				});
			}

		}
		, intiTinymce: function (myselector) {
			if (window.tinymce) {
				var selector = myselector ? myselector : '.description';
				window.tinymce.init({
					selector: selector,
					height: "300",
					plugins: "fullscreen, code",
					block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment | code | fullscreen',
				});
			}
		}
		, datePicker: function () {
			$("input[type=\"date\"]").each(function () {
				$(this).attr("type", "text");
				$(this).datepicker({
					language: langCode,
					format: "yyyy-mm-dd",
					autoclose: true,
					todayHighlight: true
				});
			});
		}
		, timePicker: function () {
			if ($(".showtimepicker").length) {
				$(".showtimepicker").timepicker();
			}
		}
		, select2: function () {
			$("select").select2({
				tags: false,
				width: "100%",
				height: "50px",
			});
		}
		, customCheckbox: function () {
			var checkboxs = $('input[type=checkbox]');
			checkboxs.each(function () {
				$(this).wrap('<div class="customCheckbox"></div>');
				$(this).before('<span>&#10004;</span>');
			});
			checkboxs.change(function () {
				if ($(this).is(':checked')) {
					$(this).parent().addClass('customCheckboxChecked');
				} else {
					$(this).parent().removeClass('customCheckboxChecked');
				}
			});
		}
		, modalAnimation: function () {
			$(".modal").on("show.bs.modal", function (e) {
				$(".modal .modal-dialog").attr("class", "modal-dialog  flipInX  animated"); //bounceIn, pulse, lightSpeedIn,bounceInRight
			});
			$(".modal").on("hide.bs.modal", function (e) {
				$(".modal .modal-dialog").attr("class", "modal-dialog  flipOutX  animated");
			});
		}
		, generateCardNo: function (x) {
			if (!x) { x = 16; }
			var chars = "1234567890";
			var no = "";
			for (var i = 0; i < x; i++) {
				var rnum = Math.floor(Math.random() * chars.length);
				no += chars.substring(rnum, rnum + 1);
			}
			return no;
		}
		, playSound: function (name, path) {
			path = path ? path : window.baseUrl + '/assets/itsolution24/mp3/' + name;
			var audioElement = document.createElement('audio');
			audioElement.setAttribute('src', path);
			if (typeof audioElement.play === 'function') {
				audioElement.play();
			}
		}
		, getBase64FromImageUrl: function (url, callback) {
			var img = new Image();
			img.crossOrigin = "anonymous";
			img.onload = function () {
				var canvas = document.createElement("canvas");
				canvas.width = this.width;
				canvas.height = this.height;
				var ctx = canvas.getContext("2d");
				ctx.drawImage(this, 0, 0);
				var dataURL = canvas.toDataURL("image/png");
				var o = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
				callback(o);
			};
			img.src = url;
		}
		, bootBooxHeightAdjustment: function () {
			if (deviceType != 'phone') {
				$(document).find(".bootboox-container").css({ "height": $(window).height() - 115 });
				$(".bootboox-container").perfectScrollbar();
			}
		}
		, printModalPage: function (selector) {
			var $print = $(selector)
				.clone()
				.addClass('print-modal-content')
				.removeClass('modal-dialog')
				.prependTo('body');
			window.print();
			$print.remove();
		}
		, windowWidth: function () {
			return $(window).width();

		}
		, windowHeight: function () {
			return $(window).height();
		}
		, init: function () {

			// Initiate live clock
			if ($("#live_datetime").length) {
				window.liveDateTime('live_datetime');
			}

			// Initiate code editor
			this.codeEditor();

			// Initiate date picker
			this.datePicker();

			// Initiate time picker
			this.timePicker();

			// inititate select2
			this.select2();

			// Initiate customer checkbox
			// this.customCheckbox();

			// Initiate beautiful bootstrap modal animation
			this.modalAnimation();

			// Scrollbar
			$("#side-panel, .dashboard-widget, .scrolling-list, .dropdown-menu").perfectScrollbar();
			var t = setInterval(function () {
				if ($(".scrolling-list").length) {
					$(".scrolling-list").perfectScrollbar();
					clearInterval(t);
				}
			}, 500);

			//Notification options
			window.toastr.options = {
				"closeButton": true,
				"debug": false,
				"newestOnTop": false,
				"progressBar": false,
				"positionClass": "toast-bottom-left",
				"preventDuplicates": true,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "5000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};

			// Expand collapse supplier stock products
			$(".supplier_title").on("click", function () {
				$(this).hasClass("active") ? $(this).removeClass("active") : $(this).addClass("active");
				var panel = $(this).data("panel");
				$("#" + panel).toggle("fast");
			});

			// Generate random number
			$(".random_num").click(function () {
				$(this).parent(".input-group").children("input").val(storeApp.generateCardNo(8));
			});

			// Generate random card no
			$(".random_card_no").click(function () {
				$(this).parent(".input-group").children("input").val(storeApp.generateCardNo(16));
			});
			if ($(".random_card_no").length > 0) {
				setTimeout(function () {
					$(".random_card_no").trigger("click");
				}, 1000);
			}

			// Filter box
			$("#show-filter-box").on("click", function (e) {
				e.preventDefault();
				$("#filter-box").slideDown("fast");
				$("body").toggleClass("overlay");
			});

			$("#close-filter-box").on("click", function (e) {
				e.preventDefault();
				$("#filter-box").slideUp('fast');
				$("body").toggleClass("overlay");
			});

			// Generate gift card no.
			$('#genNo').click(function () {
				var no = generateCardNo();
				$(this).parent().parent('.input-group').children('input').val(no);
				return false;
			});
		}
	};
}(window.jQuery));