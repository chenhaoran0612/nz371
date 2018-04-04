<div class="fileupload">
    <!-- Redirect browsers with JavaScript disabled to the origin page -->

    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar">
        <div class="col-lg-7">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button btn-sm">
                <i class="fa fa-plus"></i>
                <span>添加文件...</span>
                <input type="file" name="files[]" multiple>
            </span>
            <button type="submit" class="btn btn-primary start btn-sm">
                <i class="fa fa-upload"></i>
                <span>开始上传</span>
            </button>
            <span class="fileupload-process"></span>
            <div class="warning_file text-danger"></div>

        </div>
        <!-- The global progress state -->
        <div class="col-lg-5 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            <!-- The extended global progress state -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>
    <!-- The table listing the files available for upload/download -->
    {{-- <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table> --}}
    <div class="files"></div>
</div>



<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="template-upload" style="position: relative; overflow: hidden; border: 1px solid #c0c0c0; margin-right: 10px; width: 180px; height: 180px; margin-bottom: 10px; float: left;">

        <div class="preview" style="width: 180px;"></div>
        <div style="position: absolute; top: 0px; right: 5px;">
            {% if (!i && !o.options.autoUpload) { %}
                <i class="fa fa-upload start"></i>
            {% } %}

            {% if (!i) { %}
                <i class="fa fa-trash cancel text-danger" onClick="imageDelete(this)"></i>
            {% } %}
        </div>
        
    </div>
{% } %}
</script>

<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}

    <div class="pull-left template-download" style="position: relative; overflow: hidden; border: 1px solid #c0c0c0; margin-right: 10px; width: 180px; height: 180px; margin-bottom: 10px;">
        <div class="preview">
            {% if (file.thumbnailUrl) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery style=" background: url({%=file.thumbnailUrl%}) no-repeat center center; display: block; width: 180px; height: 180px; background-size:contain; overflow: hidden;"></a>
            {% } %}
        </div>

        <div style="position: absolute; top: 0px; right: 5px;">
            <a class="fa fa-trash text-danger delete" onClick="imageDelete(this)"></a>
        </div>
    </div>

{% } %}
</script>


