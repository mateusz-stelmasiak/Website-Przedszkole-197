   //get url argument value by name
function getUrlParam(param)
{
    var url_string = window.location.href;
    var url = new URL(url_string);
    return url.searchParams.get(param);
}

    //lists holding currently selected filtering criteria
    var years=['all'];
    var groups=['all'];
    //check url for preliminary year filtering

    var year =getUrlParam(year);
    if(year!=null){ toogleYear(year);}


    filter();

    function checkAllBoxes(n){
        var checkBoxes = document.getElementsByClassName(n+"FilterBox");
        for (z = 0; z < checkBoxes.length; z++)
        {
            if(!checkBoxes[z].checked){checkBoxes[z].click();}
        }
    }

   function uncheckAllBoxes(n){
       var checkBoxes = document.getElementsByClassName(n+"FilterBox");
       for (g = 0; g < checkBoxes.length; g++)
       {
           checkBoxes[g].checked=false;
       }
   }

   function uncheckAllBox(n){
       var checkBox = document.getElementById(n+"AllBox");
       checkBox.checked=false;
   }

   function checkAllBox(n){
       var checkBox = document.getElementById(n+"AllBox");
       checkBox.checked=true;
   }

   function setBoxWithAttribute(att,state){
       var checkBox = document.getElementById(att+"Box");
       checkBox.checked=state;
   }
   
    function toogleYear(y)
    {
        //if all was just added, clear list to just all and deselect all other
        if(y=='all' && years[0]!='all'){years=['all']; uncheckAllBoxes('y'); checkAllBox('y'); filter();return;}
        //if all was on list before, deselect it and toogle all other boxes
        if(y=='all' && years[0]=='all'){checkAllBoxes('y'); return;}

        //if something else was added and all is present, delete the all
        if(y!='all' && years[0]=='all'){years.splice(0, 1); uncheckAllBox('y');}

        //check if it is in the list, then delete it
        const index = years.indexOf(y);
        if (index > -1){years.splice(index, 1);}
        //if it isn't add it to the filtering list
        else{ years.push(y);}

        //if all checkboxes have been unchecked
        if(years.length==0){toogleYear('all');}
        filter();
    }

    function toogleGroup(g)
    {
        //if all was just added, clear list to just all and deselect all other
        if(g=='all' && groups[0]!='all'){groups=['all']; uncheckAllBoxes('g'); checkAllBox('g');  filter();return;}
        //if all was on list before, deselect it and toogle all other boxes
        if(g=='all' && groups[0]=='all'){checkAllBoxes('g'); return;}

        //if something else was added and all is present, delete the all
        if(g!='all' && groups[0]=='all'){groups.splice(0, 1); uncheckAllBox('g');}


        //check if it is in the list, then delete it
        const index = groups.indexOf(g);
        if (index > -1){groups.splice(index, 1);}
        //if it isn't add it to the filtering list
        else{ groups.push(g);}

        //if all checkboxes have been unchecked
        if(groups.length==0){toogleGroup('all');}
        filter();
    }

    function filter(){
        //get all the albums
        var albums = document.getElementsByClassName("album");
        var yearContainers = document.getElementsByClassName("yearContainer");
        //start by displaying all
        for (i = 0; i < yearContainers.length; i++)
        {
            yearContainers[i].style.display='flex';
        }
        for (i = 0; i < albums.length; i++)
        {
            albums[i].style.display='block';
        }
        filterByYear(yearContainers);
        filterByGroup(albums);
        return;
    }

    function filterByYear(yearContainers) {
        //check for 'all' value
        if(years[0]=='all'){return;}

        for (i = 0; i < yearContainers.length; i++){
            var year=yearContainers[i].getAttribute('year');
            var displayFlag=0;
            for(j = 0; j<years.length;j++){
                if(year==years[j])
                {
                    displayFlag=1;
                    break;
                }
            }
            if(displayFlag==1){yearContainers[i].style.display='flex';}
            else{yearContainers[i].style.display='none';}
        }
    }

    function filterByGroup(albums){
         //check for 'all' value
        if(groups[0]=='all'){return;}


        for (var i = 0; i < albums.length; i++){
            var groupTag=albums[i].getAttribute('groupTag');
            var displayFlag=false;
            if(groupTag!=null){
                var groupTags=groupTag.split(',');

                for(g=0;g<groupTags.length;g++)
                {
                    for(j = 0; j < groups.length; j++)
                    {
                        if(groupTags[g]==groups[j])
                        {
                            displayFlag=true;
                            break;
                        }
                    }
                    if(displayFlag){break;}
                }
            }
            if(displayFlag){albums[i].style.display='block';}
            else{albums[i].style.display='none';}
        }
    }

    function showDropDown(name){
       document.getElementById(name).style.display= 'block';
    }
    function hideDropDown(name){
       document.getElementById(name).style.display= 'none';
    }

   function toogleFilters(){
       var container = document.getElementsByClassName("filtersContainer")[0];
       var arrow = document.getElementsByClassName("showButton")[0];
       var displayStatus=container.style.display;


       if(displayStatus=='flex'){
           container.style.display='none';
           arrow.style.backgroundImage= 'url("graphics/arrowRight.png")';
       }
       else{
           container.style.display='flex';
           arrow.style.backgroundImage= 'url("graphics/arrowLeft.png")';
       }
   }