$(document).ready(function(){

	$('#meta_input').filer({
		extensions: ['xml'],
		showThumbs: false,
		addMore: false,
		allowDuplicates: false,
		captions: {
			feedback: "Choose XML metadata file To Upload",
			errors: {
				filesType: "Only XML is allowed to be uploaded."
			}
		}

	});

	$('#filer_input').filer({
		showThumbs: true,
		addMore: false,
		allowDuplicates: false
	});

	// $('#form_upload').submit( function ( e ) {
	// 	var metafiles  = $('#meta_input').val();
	// 	var files = $('#filer_input').val();
	// 	//var submit = $(this).serialize();
	// 	// Create a formdata object and add the files
	// 	var data = new FormData();
	// 	$.each(files, function(key, value)
	// 	{
	// 		data.append(key, value);
	// 	});
	// 	console.log( data );
	// 	e.preventDefault();
	// 	e.stopPropagation();
	// 	$.ajax({
	// 		url: 'form_upload.php',
	// 		data: new FormData(this), //'metafiles='+ metafiles + '&files='+files,
	// 		type: 'POST',
	// 		enctype: 'multipart/form-data',
	// 		contentType: false,       // The content type used when sending data to the server.
	// 		cache: false,             // To unable request pages to be cached
	// 		processData:false,        // To send DOMDocument or non processed data file it is set to false
	// 		//synchron: true,
	// 		success: function(data, itemEl, listEl, boxEl, newInputEl, inputEl, id){
	// 			new_file_name = JSON.parse(data);
	// 			console.log( new_file_name );
	// 			// var parent = itemEl.find(".jFiler-jProgressBar").parent(),
	// 			// 	new_file_name = JSON.parse(data),
	// 			// 	filerKit = inputEl.prop("jFiler");
     //            //
	// 			// filerKit.files_list[id].name = new_file_name;
     //            //
	// 			// itemEl.find(".jFiler-jProgressBar").fadeOut("slow", function(){
	// 			// 	$("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");
	// 			// });
	// 		},
	// 		error: function(el){
	// 			// var parent = el.find(".jFiler-jProgressBar").parent();
	// 			// el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
	// 			// 	$("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");
	// 			// });
	// 		}
	// 	});
	// 	e.preventDefault();
	// });

});
