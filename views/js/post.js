/*
    Authors: Quentin Guenther, Kianna Dyck, Jen Shin, Bessy Torres-Miller
    Date: 04/10/2018
    Name of File: post.js
    Purpose: This page initializes quill editor.
*/

/**
 * This module has functionality to add QuillJS to a page.
 *
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 */
var Post = (function() {
    const EDITOR_REL = "[rel='quill-editor']";
    const READER_REL = "[rel='quill-reader']";
    const PROGRESS_BAR_ID = ".progress-bar";
    const MAX_POST_SIZE = 7000000; // Size in bytes

    /**
     * This function scans the page for HTML elements
     * with the ref type of "quill-editor" then creates a new QuillJS object
     * on that element. Attributes which are associated with editing
     * capabilities are then assigned to the QuillJS object.
     *
     * @return {NULL}
     */
    function createEditor() {
        var editor = new Quill(EDITOR_REL, {
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ align: [] }],
                    ['image'],
                    [{ list: 'ordered' }, { list: 'bullet' }]
                ]
            },
            placeholder: 'Compose an epic...',
            theme: 'snow'
        });

        var updateProgressBar = function(percent) {
            $(PROGRESS_BAR_ID).attr('aria-valuenow', percent + '%').css('width', percent + '%').html(percent + '%');

            if(percent < 50) {
                $(PROGRESS_BAR_ID).removeClass('bg-success bg-warning bg-danger');
                $(PROGRESS_BAR_ID).addClass('bg-success');
            } else if(percent >= 50 && percent < 80) {
                $(PROGRESS_BAR_ID).removeClass('bg-success bg-warning bg-danger');
                $(PROGRESS_BAR_ID).addClass('bg-warning');
            } else {
                $(PROGRESS_BAR_ID).removeClass('bg-success bg-warning bg-danger');
                $(PROGRESS_BAR_ID).addClass('bg-danger');
            }
        };

        var updateSubmitButton = function(percent) {
            if(percent >= 100) {
                $('#submit').prop('disabled', true);
                $('#submit').addClass('btn-secondary');
                $('#submit').removeClass('bg-green-river-green');
            } else {
                $('#submit').prop('disabled', false);
                $('#submit').addClass('bg-green-river-green');
                $('#submit').removeClass('btn-secondary');
            }
        };

        editor.on('text-change', function(delta, oldDelta, source) {
            var deltaLength = JSON.stringify(editor.getContents()).length;
            var percentUsed = Math.ceil(deltaLength / MAX_POST_SIZE * 100);

            updateProgressBar(percentUsed);
            updateSubmitButton(percentUsed);
        });

        // Get the path of the page. 1-... indicates a view post page,
        // otherwise the create-post page is indicated.
        var path = window.location.pathname;
        var page = path.split("/").pop();
        
        // Get the content from get-post/page
        // or from the session if not a view post page
        if(!isNaN(parseFloat(page)) && isFinite(page)) {
            editor.setContents(getQuillContent(page, 1));
        } else {
            editor.setContents(getQuillContent("session", 0));
        }

        // sending an empty string to getQuillContent causes an error since /get-post route now has a parameter
        quillFocusHandler(editor);
        quillSubmitHandler(editor);
    }

    /**
     * This function gets the contents of a Quill object and returns
     * the string representation of JSON form to the server.
     *
     * @param {Quill} editor An instance of Quill which will be submitted to the server.
     * @return true if success.
     */
    function quillSubmitHandler(editor) {
        var form = document.querySelector('form');
        form.onsubmit = function() {
            //populate hidden form on submit
            var newpost = document.querySelector('input[name=new-post]');
            newpost.value = JSON.stringify(editor.getContents());

            console.log("Success", $(form).serialize(), $(form).serializeArray());

            return true;
        }
    }

    /**
     * This function sets the focus of the QuillJS editor when the element is clicked on.
     *
     * @param {Quill} editor An instance of Quill being used as an editor.
     */
    function quillFocusHandler(editor) {
        $(EDITOR_REL).click(function() {
            editor.focus();
        });
    }

    /**
     * This function scans the page for HTML elements
     * with the ref type of "quill-reader" then creates a new QuillJS object
     * on that element. Attributes which are associated with read only
     * capabilities are then assigned to the QuillJS object.
     *
     * @return {NULL}
     */
    function createReader() {
        var reader =new Quill(READER_REL, {
            theme: 'bubble'
        });

        // var postId = window.location.href.split("/").pop();;
        var postId = $("#postId").val();
        reader.setContents(getQuillContent(postId));
        reader.enable(false);
    }

    /**
     * This function sends a request for a JSON object to the server which
     * contains the QuillJS delta information.
     * The URL which the request is made to is "./get-post/{UUID}".
     *
     * @param {String} uuid The unique identifier of a post.
     * @param {int} levelsToRoot Defines how many levels to the root of the website. 0 or not 0.
     * @return {JSON} Returns a QuillJS delta.
     */
    function getQuillContent(uuid, levelsToRoot) {
        if(levelsToRoot == 0) {
            var url = "./get-post/" + uuid;
        } else {
            var url = "../get-post/" + uuid;
        }
        var delta;
        $.ajax({
            url: url,
            dataType: 'json',
            async: false,
            success: function(data) {
                delta =  JSON.parse(data);
            },
            type: 'GET'
        });
        return delta;
    }

    return {
        createEditor: createEditor,
        createReader: createReader
    };

})();