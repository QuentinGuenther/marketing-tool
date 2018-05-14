/*
Authors: Kianna Dyck
Date: 05/02/2018
File Name: register.js
Purpose: This file adds javascript functionality for the marketing tool registration page.
*/

$(document).ready(function() {
    // initially hide select and text input fields for radio options
    $("#existing-team").hide();
    $("#new-team").hide(); // label + input div

    // if radios checked (sticky after sending to POST)
    if($("#join-team").is(":checked")) {
        $("#existing-team").show();
    }

    if($("#add-team").is(":checked")) {
        $("#new-team").show();
    }

    // join team radio is selected
    $("#join-team").click(function(){
        $("#new-team").hide();
        $("#create-team").prop('disabled', true); // only input field
        $("#existing-team").show();

    });

    // create new team radio is selected
    $("#add-team").click(function(){
        $("#existing-team").hide();
        $("#create-team").prop('disabled', false);
        $("#new-team").show();
    });
});