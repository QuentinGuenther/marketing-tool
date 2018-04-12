/*
    Name: Quentin, Kianna, Jen, Bessy
    Date: 04/10/2018
    Name of File: post.js
    Purpose: This page initializes quill editor.
*/

var Post = (function() {
    // Instantiate new Quill Object
    var quill = new Quill("[ref='quil-editor']", {
        modules: {
            toolbar: [
            [{ header: [1, 2, false] }],
            ['bold', 'italic', 'underline'],
            ['image', 'code-block']
            ]
        },
        placeholder: 'Compose an epic...',
        theme: 'snow'  // or 'bubble'
    });



    // constructor
    function init() {

        quill;

    }

    // public api
    return {
        init: init

    };


})();

