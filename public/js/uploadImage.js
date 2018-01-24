document.write('<link rel="stylesheet" href="/libs/blueimp-file-upload/css/jquery.fileupload.css">');
document.write('<link rel="stylesheet" href="/libs/blueimp-file-upload/css/jquery.fileupload-ui.css">');
document.write('<link rel="stylesheet" href="/libs/blueimp-gallery/css/blueimp-gallery.min.css">');

// <!-- 上传组件依赖 -->
// <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
document.write('<script src="/libs/blueimp-file-upload/js/vendor/jquery.ui.widget.js"></script>');
// <!-- The Templates plugin is included to render the upload/download listings -->
document.write('<script src="/libs/blueimp-tmpl/js/tmpl.min.js"></script>');
// <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
document.write('<script src="/libs/blueimp-load-image/js/load-image.all.min.js"></script>');
// <!-- The Canvas to Blob plugin is included for image resizing functionality -->
document.write('<script src="/libs/blueimp-canvas-to-blob/js/canvas-to-blob.min.js"></script>');
// <!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
// {{-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> --}}
// <!-- blueimp Gallery script -->
document.write('<script src="/libs/blueimp-gallery/js/jquery.blueimp-gallery.min.js"></script>');
// <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
document.write('<script src="/libs/blueimp-file-upload/js/jquery.iframe-transport.js"></script>');
// <!-- The basic File Upload plugin -->
document.write('<script src="/libs/blueimp-file-upload/js/jquery.fileupload.js"></script>');
// <!-- The File Upload processing plugin -->
document.write('<script src="/libs/blueimp-file-upload/js/jquery.fileupload-process.js"></script>');
// <!-- The File Upload image preview & resize plugin -->
document.write('<script src="/libs/blueimp-file-upload/js/jquery.fileupload-image.js"></script>');
// <!-- The File Upload audio preview plugin -->
document.write('<script src="/libs/blueimp-file-upload/js/jquery.fileupload-audio.js"></script>');
// <!-- The File Upload video preview plugin -->
document.write('<script src="/libs/blueimp-file-upload/js/jquery.fileupload-video.js"></script>');
// <!-- The File Upload validation plugin -->
document.write('<script src="/libs/blueimp-file-upload/js/jquery.fileupload-validate.js"></script>');
// <!-- The File Upload user interface plugin -->
document.write('<script src="/libs/blueimp-file-upload/js/jquery.fileupload-ui.js"></script>');

function initUploadImage(parentClassName, getImageUrl, previewMaxWidth, previewMaxHeight)
{
	// Initialize the jQuery File Upload widget:
	$('.'+parentClassName+' .fileupload').fileupload({
	    // Uncomment the following to send cross-domain cookies:
	    //xhrFields: {withCredentials: true},
	    url: getImageUrl
	});
	
	// Load existing files:
	$('.'+parentClassName+' .fileupload').addClass('fileupload-processing');
	$.ajax({
	    // Uncomment the following to send cross-domain cookies:
	    //xhrFields: {withCredentials: true},
	    url: $('.'+parentClassName+' .fileupload').fileupload('option', 'url'),
	    dataType: 'json',
	    context: $('.'+parentClassName+' .fileupload')[0]
	}).always(function () {
	    $(this).removeClass('fileupload-processing');
	}).done(function (result) {
	    $(this).fileupload('option', 'done')
	            .call(this, $.Event('done'), {result: result});
	});

	$('.'+parentClassName+' .fileupload').fileupload('option', {
	     maxFileSize: 999000,
	     previewMaxWidth: previewMaxWidth,
	     previewMaxHeight: previewMaxHeight,
	     acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
	});
	
}

function getUploadImage(parentClassName){
	var images = '';
	$('.'+parentClassName+' .fileupload .template-download').each(function(k, v){
	    curImage = $(this).find('.preview').find('a').attr('href');
	    if (curImage) {
	        images += curImage+',';
	    }
	})
	return images.substring(0,images.length-1);
}