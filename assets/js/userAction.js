//button provider--->subscribe button functiom

/*----------Note------
$action="subscribe(\"$userTo\",\"$userLoggedIn\",this)
--*/

//this is include in header file because it might use later
//this-> refers to clicked element
function subscribe(userTo, userFrom, button){
    if(userTo == userFrom){
        alert("You can't subscribe to yourself ");
        return;
    }

        //ajax
        $.post("ajax/subscribe.php" ,{userTo: userTo,userFrom: userFrom})
        .done(function(dataReceived){
            //returnning and printing this 
            // console.log("done");

            //data recived actually sub count no
            var count=dataReceived;
            // console.log(dataReceived);
            if(count !=null){
                /*If the element has the class "subscribe", it will be removed, and "unsubscribe" will be added.
If the element has the class "unsubscribe", it will be removed, and "subscribe" will be added.
*/ 
                $(button).toggleClass("subscribe unsubscribe");
                let buttonText=$(button).hasClass('subscribe') ? "SUBSCRIBE" : "SUBSCRIBED";

                $(button).text(buttonText + " " + count);
            } else{
                alert("something went wrong! check userAction.js");
            }
        });
    
}