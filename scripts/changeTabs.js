var sections = document.getElementsByClassName("section");
var buttons = document.getElementsByClassName("tabSwitch");

   //get url argument value by name
function getUrlParam(param){
    var url_string = window.location.href;
    var url = new URL(url_string);
    return url.searchParams.get(param);
}

function currentlyOnSubpage()
{
    var id = getUrlParam('albumID');
    var dd= getUrlParam('newsID');

    if(id!=null || dd!=null){ return true;}
    return false;
}

//for debugging
function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = escape(name) + "=" +
        escape(value) + expires + "; path=/";
}


function changeTab(section,e){
    //hide all first
    for (i = 0; i < sections.length; i++){ sections[i].style.display= 'none';}

    //if one is switching off from an album
    if(section!='album' && section!='aktualnosc'){
        window.history.pushState("", "", "/?section=".concat(section));
    }

    //unbold all buttonsfor
    for(i = 0; i < buttons.length; i++)
    {
        buttons[i].style.fontWeight= 'normal';
        buttons[i].style.backgroundColor= '';
    }
    if(e!=null){
        e.style.fontWeight='bold';
        e.style.backgroundColor= '#d6702b';
    }

    //displaying successfailure message on contact
    if(section=='kontakt')
    {
        var success = getUrlParam(success);
        //scroll to bottom of the page
        if(success=='true'){ document.getElementById("contactSuccess").style.display= 'block';}
        if(success=='false'){ document.getElementById("contactFailure").style.display= 'block';}
    }
    
  
    //display the section pointed to by section argument
    document.getElementById(section).style.display= 'block';

}

if(getUrlParam('albumID')!=null){changeTab('album',null);}
else if(getUrlParam('newsID')!=null){changeTab('aktualnosc',null);}
else if(getUrlParam('section')!=null){changeTab(getUrlParam('section'),null);}
else{
    //set default tab clicked
    document.getElementById('defaultTabButton').click();
}
