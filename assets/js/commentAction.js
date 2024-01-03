

// watch.php--->postComment fn
function postComment(button,postedBy,videoId,replyTo,containerClass){
    var textarea=$(button).siblings("textarea");
    var commentText=textarea.val();
    textarea.val("");////Emptying textarea once btn is clicked
    // console.log(commentText);


    //if textaarea has value
    if(commentText){
        //ajax call to handle posted comment
        $.post("ajax/postComment.php",
        {commentText: commentText,
            postedBy: postedBy,
            videoId: videoId,
            responseTo: replyTo})
        .done(function(comment){
            // if postComment file found
            // alert("Fine ✅, postComment.php found");
            if(!replyTo) {
                $("." + containerClass).prepend(comment);
            }
            else {
                $(button).parent().siblings("." + containerClass).append(comment);
            }
        });

    }else{
        alert("You can't post empty comment...😛🙃");
    }
}