/*
    Authors: Bessy Torres-Miller
    Date: 05/21/2018
    Name of File: removeTeam.js
    Purpose: This page shows a message to the user admin to confirm that he wants to remove permanently a team
*/


$('.removeTeam').each( function() {
    $(this).click(function() {
        var confirmation = confirm('Are you sure you want to delete permanently a team and all its information? ' +
            'The information will no longer be available');
        if (!confirmation)
        {
            return false;
        }
    });
});

