//when page load
$(document).ready(function(){

    //side nav toggle
    $(".navShowHide").on("click",function(){
        var nav=$(".sideNavContainer");
        var main=$(".mainSectionContainer");

        if(main.hasClass("leftPadding")){
            nav.hide();
            console.log("nav hide...");
        }
        else{
            nav.show();
            console.log("nav show...");

        }

        main.toggleClass("leftPadding");

    });

  
});
// from button provider.php to give alart if not sign in not able to like and dislike 
function notSignedIn(){
    alert("You must be create account or logged in to perform this action");
}