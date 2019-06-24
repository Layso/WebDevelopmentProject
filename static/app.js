/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*var firstname =$("input[name|='firstname']").val();
 console.log(firstname);*/
/*var validName= function(name){
 var letters = /^[A-Za-z]+$/;
 if(name.toString().match(letters)){
 return true;
 }
 else{
 throw new Error()
 return false;
 }
 }
 var validfMdp = function(mdp){
 
 return mdp.length>=4;
 
 }
 
 var btnConfirm= $("#formRegister input[name='submit']").click(function(){
 var pwd = $("#formRegister input[name='pwd']");
 var confirmPwd = $("#formRegister input[name='confirmPwd']").val();
 var firstname = $("#formRegister input[name='firstname']").val();
 if(validfMdp(pwd.val())){
 
 }
 else{
 
 }
 });*/
/*var content = function (b) {
 console.log(b.siblings());
 }
 $(document).ready(function () {
 var $btn = $(".sheet-card form button")
 console.log($btn);
 for (var i = 0; i < $btn.length; i++) {
 var b = $btn.eq(i);
 console.log(b);
 
 
 b.click(event, function () {
 event.preventDefault();
 content(b)
 
 })
 
 
 }
 
 
 });*/
$(document).ready(function () {
    
    var recupTrainees = function($trainees){
        var tab = new Array();
        $trainees.each(function(){
            if($(this).attr("checked")==true){
                tab.prototype.push($(this).val());
            }
        });
        return tab;
        
    }
    
    
    $(".sheet-card form button").each(function () {
        //console.log( index + ": " + $( this).siblings().html() );
        $(this).click(event, function () {
            var reg = new RegExp("^ *(select)");
            //var reg2= /(?<! +["'] +)( *(update|delete|create|drop,alter) *)(?!["'])/;
//            var reg2= new RegExp("(?<! +[\"\'] +|[A-Za-z])( *(update|delete|create|drop|alter) *)(?![A-za-z]|[\"\'])");
            event.preventDefault();
            var $answer = $(this).siblings("textarea");
            var $question_id = $(this).siblings("input");
            console.log($answer);
            if ($answer.val() === "" || !($answer.val().toLowerCase().match(reg))) {
                console.log("Non");
            } else {
                var data = {answer: $answer.val(), question_id: $question_id.val(), evaluation_id: e, "trainee_id": t}
                /*var $post = $.post(
                 //url
                 "../sheet_answer_controller.php",
                 data
                 );
                 $post.done(function () {
                 alert("Data Loaded: " + "");
                 $answer.siblings("button").prop("disabled", true);
                 $answer.prop("disabled", true);
                 });*/
                /**
                 * 
                 * @type jqXHR^\s*(select)
                 */
                var put = $.ajax(
                        {
                            url: "sheet_answer_controller.php",
                            type: "PUT",
                            data: data,
                            success: function () {
                                alert("Data Loaded: " + "");
                                //$answer.siblings("button").prop("disabled", true);
                                // $answer.prop("disabled", true);
                            }

                        });

            }
        });
    });
    $('.sheet').children("a").click(event, function (event) {
        var event = event;
        event.preventDefault();
        if (window.confirm("Êtes-vous sûr de vouloir terminer l'évaluation?")) {

            var data = {state: "completed"};
            var url = "evaluation-" + e + ".php";
            $.ajax({
                url: "trainee_sheet_controller.php",
                type: "PUT",
                data: data,
                success: function () {
                    window.location.replace("../");
                }
            });
        }
    });

    if ($(".sheet").length !== 0) {
        window.addEventListener("beforeunload", function (event) {
            var confirmationMessage = "Êtes-vous sûr de vouloir terminer l'évaluation?";

            event.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
            return confirmationMessage;
        });
    }
    console.log($(".groups-open .group form"));
    window.console.log($(".groups-open .group form "));

    $(".groups-open .group form ").each(function () {
        $(this).submit(event, function () {
            event.preventDefault();
            $groupid = $(this).children("input");
            console.log($groupid.val());
            if (confirm("Are you sure to close this group?")) {
                var data = {groupid: $groupid.val()};
                var url = "trainer-" + e + "_closeGroup";
                $.ajax({
                    url: url,
                    type: "PUT",
                    data: data,
                    success: function () {
                        document.location.reload(true);
                    },
                    error: function () {
                        alert(" The server endure the following error: there is a problem with a PUT request");
                    }

                });

            }

        });
    });

    $(".groups_closed .group form ").each(function () {
        $(this).submit(event, function () {
            event.preventDefault();
            $groupid = $(this).children("input");
            console.log($groupid.val());
            if (confirm("Are you sure to reopen this group?")) {
                var data = {groupid: $groupid.val()};
                var url = "trainer-" + e + "_reopenGroup";
                $.ajax({
                    url: url,
                    type: "PUT",
                    data: data,
                    success: function () {
                        document.location.reload(true);
                    },
                    error: function () {
                        alert(" The server endure the following error: there is a problem with a PUT request");
                    }

                });

            }

        });
    });
    
    $(".group-candidates form button[name='validate']").click(event, function (event) {
        //console.log($(this).siblings(".candidates-list").children("li").children("input"));
        $trainees=$(this).siblings(".candidates-list").children("li").children("input");
        tab= new Array();
        self=$(this);
        $trainees.each(function(){
            if($(this).prop("checked")){
                //tab=$(this).val();
                tab.push($(this).val());
            }
        });
        console.log(tab);
        if(tab.length >0){
            url="trainer-" + e + "_group-"+g+"_validate";
            data={trainees:tab};
            $.ajax({
                    url: url,
                    type: "PUT",
                    data: data,
                    success: function () {
                        //document.location.reload(true);
                    },
                    error: function () {
                        alert(" The server endure the following error: there is a problem with a PUT request");
                    }

                });
        }else{
            window.alert("You need to select a trainee");
        }
         
        
    });
    
    
    


});
