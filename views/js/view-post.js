/*
    Authors: Quentin Guenther, Jen Shin, Kianna Dyck
    Date: 04/28/2018
    Name of File: view-post.js
    Purpose: This page creates an instance of a read-only post with data retrieved from database.
*/

$(document).ready(function() {
    Post.createReader();
    vote();
});

/**
 * This function sends a query to update the database with a user's vote for a specific project,
 * then updates the html page with jQuery for the project/post count if successful.
 */
function vote()
{
    $("#vote").click(function(){

        // Number of votes available for logged-in user
        var availableVotes = $('#totalUserVotes').val();

        if (availableVotes === 0)
        {
            alert("No more available votes!");

        } else {
            // userId of logged-in user and postId for project idea
            var userId = $("#userId").val();
            var postId = $("#postId").val();

            // send ajax call to update database with new vote
            $.ajax({
                type: "post",
                url: "../addVote",
                data: {'userId' : userId,
                    'postId' : postId},
                success: function(response) {
                    if (response === "success") {
                        /* Update the vote count */
                        var count = $("#count");
                        var previousCount = parseInt(count.text());
                        count.text(previousCount+1);

                        // subtract from student's total available votes
                        $('#totalUserVotes').val(availableVotes - 1);

                        // change icon color to reflect student has voted for this project
                        $('.fa-thumbs-up').addClass('clicked');
                    } else {
                        // display a message if user has already voted for this project
                        alert(response);
                    }

                }}); //ajax

        }

    });
}