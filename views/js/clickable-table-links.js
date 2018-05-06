/*
    Authors: Quentin Guenther
    Date: 05/06/2018
    Name of File: clickable-table-links.js
    Purpose: This file adds functionality to turn table rows to clickable links.
    table rows need the following format: <tr class='clickable-row' data-url="{url}"></tr>
*/

$(document).ready(function() {
    $("tr.clickable-row").click(function() {
        window.location = $(this).data('url');
    });
});