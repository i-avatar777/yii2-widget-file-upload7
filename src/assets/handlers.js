var FileUpload7 = {
    init: function(options){

        var selector = (typeof options.selector != 'undefined') ? options.selector : '.FileUpload7';
        var functionSuccess = (typeof options.functionSuccess != 'undefined') ? options.functionSuccess : null;
        var maxSize1 = (typeof options.maxSize != 'undefined') ? options.maxSize : '1000';
        var server1 = (typeof options.server != 'undefined') ? options.server : 'https://cloud1.i-am-avatar.com';
        var allowedExtensions1 = (typeof options.allowedExtensions != 'undefined') ? options.allowedExtensions : ['jpg', 'jpeg', 'png'];
        var accept1 = (typeof options.accept != 'undefined') ? options.accept : 'image/*';
        var data1 = (typeof options.data != 'undefined') ? options.data : {};

        var btn = $(selector).find('.upload-btn')[0];
        var wrap = $(selector).find('.pic-progress-wrap')[0];
        var picBox = $(selector).find('.picbox')[0];
        var errBox = $(selector).find('.errormsg')[0];

        var uploader = new ss.SimpleUpload({
            button: btn,
            url: server1 + '/upload2/file-upload7',
            sessionProgressUrl: server1 + '/upload2/session-progress',
            name: 'imgfile',
            multiple: true,
            multipart: true,
            maxUploads: 2,
            maxSize: maxSize1,
            dropzone: $('.buttonDragAndDrop'),
            queue: false,
            allowedExtensions: allowedExtensions1,
            data: data1,
            accept: accept1,
            debug: true,
            hoverClass: 'btn-hover',
            focusClass: 'active',
            disabledClass: 'disabled',
            responseType: 'json',
            onSubmit: function(filename, ext) {
                var prog = document.createElement('div'),
                    outer = document.createElement('div'),
                    bar = document.createElement('div'),
                    size = document.createElement('div'),
                    self = this;

                prog.className = 'prog';
                size.className = 'size';
                outer.className = 'progress progress-striped';
                bar.className = 'progress-bar progress-bar-success';

                outer.appendChild(bar);
                prog.appendChild(size);
                prog.appendChild(outer);
                wrap.appendChild(prog); // 'wrap' is an element on the page

                self.setProgressBar(bar);
                self.setProgressContainer(prog);
                self.setFileSizeBox(size);

                errBox.innerHTML = '';
                btn.value = 'Choose another file';
            },
            onSizeError: function() {
                errBox.innerHTML = 'Files may not exceed ' + maxSize1 + 'K.';
            },
            onExtError: function() {
                errBox.innerHTML = 'Invalid file type. Please select a PNG, JPG, GIF image.';
            },
            onComplete: function(file, response, btn) {
                if (!response) {
                    errBox.innerHTML = 'Unable to upload file';
                }
                if (response.success === true) {

                    if (functionSuccess !== null) functionSuccess(response);

                    // устанавливаю значение для формы
                    $(selector).find('.inputValue').val(response.url);

                    // Показываю картинку
                    picBox.innerHTML = '<img src="' + response.url + '" style="width:100%;max-width:200px">';
                } else {
                    if (response.msg)  {
                        errBox.innerHTML = response.msg;
                    } else {
                        errBox.innerHTML = 'Unable to upload file';
                    }
                }

            }
        });
    }
};


