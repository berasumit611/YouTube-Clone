

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

// WHEN WE CLICK REPLY BUTTON IT SHOW THE TEXTAREA--commentControls--->>toggleReply(this)
function toggleReply(button){
    var parent =$(button).closest(".itemContainer");
    var commentForm=parent.find(".commentForm").first();
    commentForm.toggleClass("hidden");
}

// COMMENT LIKE FUNCTIONALITY----similar to vedio like functionality
function likeComment(commentId,button,videoId){
    $.post("ajax/likeComment.php",{
        commentId: commentId,
        videoId: videoId})
    .done(function(data){

        var likeButton=$(button);
        var disLikeButton=$(button).siblings(".disLikeButton");

        likeButton.addClass("active");
        disLikeButton.removeClass("active");

        var likesCount=$(button).siblings(".likesCount");


        updateLikesValue(likesCount,data);
       


        //if unlike likes=-1
        if(likesCount < 0){
            likeButton.removeClass("active");
            likeButton.find("img:first").attr("src","assets/images/thumb-up.png");
        }
        else{
            likeButton.find("img:first").attr("src","assets/images/thumb-up-active.png");
        }

        disLikeButton.find("img:first").attr("src","assets/images/thumb-down.png");


    });
}
function disLikeComment(commentId,button,videoId){
    $.post("ajax/disLikeComment.php",{
        commentId: commentId,
        videoId: videoId})
    .done(function(data){

      
        let disLikeButton = $(button);
        let likeButton = $(button).siblings(".likeButton");

        disLikeButton.addClass("active");
        likeButton.removeClass("active");


       

        var likesCount=$(button).siblings(".likesCount");
        updateLikesValue(likesCount,data);

        
        if(likesCount > 0){
            disLikeButton.removeClass("active");
            disLikeButton.find("img:first").attr("src","assets/images/thumb-down.png");
        }
        else{
            disLikeButton.find("img:first").attr("src","assets/images/thumb-down-active.png");
        }

        likeButton.find("img:first").attr("src","assets/images/thumb-up.png");


    });
}
//update like value
function updateLikesValue(element,num){
    var likeCountVal=element.text() ||  0;
    element.text(parseInt(likeCountVal) + parseInt(num));

}
