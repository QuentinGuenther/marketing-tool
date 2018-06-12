/*
    Authors: Quentin Guenther, Jen Shin, Kianna Dyck
    Date: 04/28/2018
    Name of File: view-post.js
    Purpose: This page creates an instance of a read-only post with data retrieved from database.
*/

$(document).ready(function() {
    Post.createReader();
    vote();
    setAsCurrentVersion();
});

/**
 * This function sends a query to update the database with a user's vote for a specific project,
 * then updates the html page with jQuery for the project/post count if successful.
 */
function vote()
{
    // update value for number input on change
    $("#vote").change(function(){
        $(this).attr("value", $("#vote").val());
    });

    // click the button!
    $("#voteForThis").click(function() {

        // current votes
        var currentVoteCount = $('#currentVoteCount').val();
        // Number of votes available for logged-in user
        var availableVotes = $('#totalUserVotes').val();

        var vote = parseInt(currentVoteCount) + parseInt(availableVotes);

        if ($("#vote").val() < 0 || $("#vote").val() > vote)
        {
            alert("Votes must be a positive number between 1 and 10. You currently have " + availableVotes +
            " vote(s) left to distribute.");
        }else {
            // userId of logged-in user and postId for project idea
            var userId = $("#userId").val();
            var postId = $("#postId").val();

            // send ajax call to update database with new vote
            $.ajax({
                type: "post",
                url: "../addVote",
                data: {
                    'userId': userId,
                    'postId': postId,
                    'inputVote': $("#vote").val()
                },
                success: function (response) {
                    if (response === "unsuccessful") {
                        // display a message if there is a database error
                        alert("Error saving vote. Please try again later.");
                    } else {

                        /* Update the vote count */

                        /* This updates the vote count, available votes, and totalVotesMade by user for a post */
                        var parsed = JSON.parse(response);

                        /* update vote count */
                        $("#count").text(parsed["totalPostCount"]);
                        /* update available */
                        $("#totalUserVotes").val(parsed["availableUserVotes"]);
                        /* update total votes from user */
                        $("#currentVoteCount").val(parsed["totalVotesForPostFromUser"]);
                    }

                }
            });
        }
    });
}

function setAsCurrentVersion()
{
    // if button exists
    if ($("#" + "setAsCurrent").length !== 0) {
        $("#setAsCurrent").one("click", function() {
            var postId = $("#postId").val();

            $.ajax({
                type: "post",
                url: "../set-new-current",
                data: {'postId' : postId},
                success: function(response) {
                    if (response === "success") {
                        // Remove current from list
                        $("#current").text("");
                        // replace button with edit button
                        $('#setAsCurrent').prop('id', 'edit').text("Edit").addClass("bg-green-river-green").removeClass("btn-primary").attr('onclick', 'toggleEditor()');
                    } else {
                        alert(response);
                    }

                }}); //ajax
        });
    }
}
