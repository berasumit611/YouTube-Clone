
// onclick fn
function likeVideo(button,videoId){
    // alert("button pressed ✅");

    //ajax
    //likevideo(hello)--->function-->shoe hello
    // $.post("ajax/likeVideo.php")
    // .done(function(data){
    //     alert(data);
    // });

    $.post("ajax/likeVideo.php",{videoId:videoId})
    .done(function(data){
        // alert(data);

        //update the buttons
        var likeButton=$(button);
        var disLikeButton=$(button).siblings(".disLikeButton");

       

        likeButton.addClass("active");
        disLikeButton.removeClass("active");

        // console.log(data);{"likes":-1,"dislikes":0}
        // The Object, Array, string, number, boolean, or null value corresponding to the given JSON text.
        var result = JSON.parse(data);
        console.log(result); 
        // {likes: -1, dislikes: 0} son obj
        updateLikesValue(likeButton.find(".text"),result.likes);
        updateLikesValue(disLikeButton.find(".text"),result.dislikes);


        //if unlike likes=-1
        if(result.likes < 0){
            likeButton.removeClass("active");
            likeButton.find("img:first").attr("src","assets/images/thumb-up.png");
        }
        else{
            likeButton.find("img:first").attr("src","assets/images/thumb-up-active.png");
        }

        disLikeButton.find("img:first").attr("src","assets/images/thumb-down.png");


    });

}

//dislike functionality same as like
function disLikeVideo(button,videoId){
   
    $.post("ajax/disLikeVideo.php",{videoId:videoId})
    .done(function(data){

    
        let disLikeButton = $(button);
            let likeButton = $(button).siblings(".likeButton");

        disLikeButton.addClass("active");
        likeButton.removeClass("active");


        var result = JSON.parse(data);
        console.log(result);  //json obj


        updateLikesValue(likeButton.find(".text"),result.likes);
        updateLikesValue(disLikeButton.find(".text"),result.dislikes);


        
        if(result.dislikes < 0){
            disLikeButton.removeClass("active");
            disLikeButton.find("img:first").attr("src","assets/images/thumb-down.png");
        }
        else{
            disLikeButton.find("img:first").attr("src","assets/images/thumb-down-active.png");
        }

        likeButton.find("img:first").attr("src","assets/images/thumb-up.png");


    });

}


//update like dislike
function updateLikesValue(element,num){
    var likeCountVal=element.text() ||  0;
    element.text(parseInt(likeCountVal) + parseInt(num));

}

