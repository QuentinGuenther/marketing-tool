/*
    Authors: Bessy Torres-Miller, Kianna Dyck
    Date: 04/28/2018
    Name of File: team-home.js
    Purpose: This page adds javascript functionality to team-home page.
*/

/* List Pagination */
$(document).ready(function() {
    $("div.list-group").paginathing({
        perPage: 7, //per page
        // limitPagination: 3, //how many pages show
        containerClass: 'mt-3',
        ulClass: 'pagination justify-content-center',
        liClass: 'page-item'
    });

    $(".list-group a").removeClass('d-none');
});

/* Menu Toggle event */
$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});