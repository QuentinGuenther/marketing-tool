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

        // sending an empty string to getQuillContent causes an error since /get-post route now has a parameter
        editor.setContents(""); //getQuillContent("")
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
     * @return {JSON} Returns a QuillJS delta.
     */
    function getQuillContent(uuid) {
        var url = "../get-post/" + uuid;
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