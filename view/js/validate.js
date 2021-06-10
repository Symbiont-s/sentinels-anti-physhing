$(document).ready(() => {
    let create     = $("#new");
    let change     = $("#change");
    let newlink    = $("#newlink");
    let login      = $("#login");
    let newphisher = $("#newphisher");
    let report     = $("#report");
    let requiredMessage = "Fill this field.";
    login.validate({
        rules:{
            username:{"required":true, alphanumeric:true},
            password:{"required":true, minlength:8}
        },
        messages:{
            username:{required:requiredMessage,alphanumeric:"Invalid username, only letters or numbers." },
            password:{required:requiredMessage, minlength:"Password must contain at least 8 characters."}
        }
    })
    create.validate({
        rules:{
            username:{"required":true, alphanumeric:true},
            password:{"required":true, minlength:8},
            password2:{required:true,minlength:8, equalTo:"#password"}
        },
        messages:{
            username:{required:requiredMessage,alphanumeric:"Invalid username, only letters or numbers." },
            password:{required:requiredMessage, minlength:"Password must contain at least 8 characters."},
            password2:{required:requiredMessage,minlength:"Password must contain at least 8 characters.", equalTo:"Passwords don't match."}
        }
    });
    change.validate({
        rules:{
            old:{"required":true, minlength:8},
            newpsw:{"required":true, minlength:8},
            newpsw2:{required:true,minlength:8, equalTo:"#newpsw"}
        },
        messages:{
            old:{required:requiredMessage,minlength:"Password must contain at least 8 characters." },
            newpsw:{required:requiredMessage, minlength:"Password must contain at least 8 characters."},
            newpsw2:{required:requiredMessage,minlength:"Password must contain at least 8 characters.", equalTo:"Passwords don't match."}
        }
    });
    newlink.validate({
        rules:{
            url:{required:true}
        }, 
        messages:{
            url:{required:requiredMessage }
        }
    });
    newphisher.validate({
        rules:{
            phisher:{required:true}
        },
        messages:{
            phisher:{required:requiredMessage}
        } 
    });
    report.validate({
        rules:{
            subject:{required:true, lettersonly:true},
            link:{required:true},
            phisher:{required:true},
            explanation:{required:true}
        },
        messages:{
            subject:{required:requiredMessage, lettersonly:"Please only letters"},
            link:{required:requiredMessage},
            phisher:{required:requiredMessage},
            explanation:{required:requiredMessage}
        }
    })
});
