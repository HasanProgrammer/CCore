$(document).ready(function ()
{

    $(this).on('click', function (ev)
    {
        var Target = ev.target;

        if(!$(Target).is("div#Box_Title_Input_Username") && !$(Target).is("input[name='username']"))
        {
            if($("div#Box_Title_Input_Username").css('margin-top') === '-45px')
            {
                if($("input[name='username']").val() === '')
                {
                    $("div#Box_Title_Input_Username").css({marginTop:'0px', transition:'0.222s', cursor:'text'}).find('div#Title_Input_Username').css({color:'rgb(200,200,200)', transition:'0.222s'});
                    $("input[name='username']").blur();
                    $("div#Inner_Border_Box_Input_Username").css({width:"0%", transition:"0.222s"});
                }
            }
        }

        if(!$(Target).is("div#Box_Title_Input_Password") && !$(Target).is("input[name='password']"))
        {
            if($("div#Box_Title_Input_Password").css('margin-top') === '15px')
            {
                if($("input[name='password']").val() === '')
                {
                    $("div#Box_Title_Input_Password").css({marginTop:'60px', transition:'0.222s', cursor:'text'}).find('div#Title_Input_Password').css({color:'rgb(200,200,200)', transition:'0.222s'});
                    $("input[name='password']").blur();
                    $("div#Inner_Border_Box_Input_Password").css({width:"0%", transition:"0.222s"});
                }
            }
        }

    });

    $("div#Box_Title_Input_Username").on('click', function ()
    {
        $(this).css({marginTop:'-45px', transition:'0.222s', cursor:'default'});
        $("input[name='username']").focus();
        $("div#Inner_Border_Box_Input_Username").css({width:"100%", transition:"0.222s"});
    });

    $("div#Box_Title_Input_Password").on('click', function ()
    {
        $(this).css({marginTop:'15px', transition:'0.222s', cursor:'default'});
        $("input[name='password']").focus();
        $("div#Inner_Border_Box_Input_Password").css({width:"100%", transition:"0.222s"});
    });

});