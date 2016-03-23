
function validateSignUp(){
	var firstname = document.getElementById('fname').value
	var lastname = document.getElementById('lname').value
	var email = document.getElementById('email').value
	var password = document.getElementById('password').value
	var password2 = document.getElementById('password2').value
	if (firstname==""){
		return false
	}else if(lastname==""){
		return false
	}else if(email==""){
		return false
	}else{
		if(password!=password2){
			return false
		}
		return true;
	}
}


/*Modified version of modal jquery from https://codyhouse.co/redirect/?resource=login-signup-modal-window*/
jQuery(document).ready(function($){
	var formModal = $('#sign-in-modal'),
    formLogin = formModal.find('#modal-login'),
    formSignup = formModal.find('#modal-signup'),
    //formForgotPassword = formModal.find('#cd-reset-password'),
    formModalTab = $('.modal-switcher'),
    tabLogin = formModalTab.children('li').eq(0).children('a'),
    tabSignup = formModalTab.children('li').eq(1).children('a'),
    logIn = $('.site-login');
    signUp = $('.site-signUp');

	    	 //open modal
    logIn.on('click', function(event){

        $('.sign-in-modal').toggleClass('is-visible');
    });

    //open sign-up form
    signUp.on('click', signup_selected);
    //open login-form form
    logIn.on('click', login_selected);

    //close modal
    formModal.on('click', function(event){
        if( $(event.target).is(formModal) || $(event.target).is('.cd-close-form') ) {
            formModal.removeClass('is-visible');
        }   
    });

    //switch from a tab to another
    formModalTab.on('click', function(event) {
        event.preventDefault();
        ( $(event.target).is( tabLogin ) ) ? login_selected() : signup_selected();
    });

    function login_selected(){
        //mainNav.children('ul').removeClass('is-visible');
        formModal.addClass('is-visible');
        formLogin.addClass('is-selected');
        formSignup.removeClass('is-selected');
        //formForgotPassword.removeClass('is-selected');
        tabLogin.addClass('selected');
        tabSignup.removeClass('selected');
    }

    function signup_selected(){
        //mainNav.children('ul').removeClass('is-visible');
        formModal.addClass('is-visible');
        formLogin.removeClass('is-selected');
        formSignup.addClass('is-selected');
        //formForgotPassword.removeClass('is-selected');
        tabLogin.removeClass('selected');
        tabSignup.addClass('selected');
    }


    function checkPasswords(){
		var password = document.getElementById('password').value
		var password2 = document.getElementById('password2').value
		if(password!=password2){
			$('#pass2Label').html("Enter Password Again: <span style='color:red;'>Passwords do not match!</span>")
		}else{
			$('#pass2Label').html("Enter Password Again:")
		}
	}

	$('#password2').keyup(function(){checkPasswords();});
});