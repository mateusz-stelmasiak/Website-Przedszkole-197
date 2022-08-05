// usage example
i=0;
//values for the clouds
//var initialPositions = new Array(60, 5, 70, 20);
var initialPositions = new Array(100, 90, 5, 80);
detectSwipe('swipeme', (el, dir) =>{
    //detect only left or right motion
    if(dir=='up'|| dir=='down')
    {
        if (dir=='down')
        {
             //if you were on the first slide save cloud location
            if(i==0)
             {
                for(p=0;p<4;p++)
                {
                //alert(Math.floor((document.getElementById("cloud"+p).getBoundingClientRect().left*100.00)/(Math.max(document.documentElement.clientWidth, window.innerWidth || 0))));
                initialPositions[p]=Math.floor((document.getElementById("cloud"+p).getBoundingClientRect().left*100.00)/(Math.max(document.documentElement.clientWidth, window.innerWidth || 0)));
                }

             }
            //if the movement was 'down' increment the counter
            i=i+1;if(i>2){i=2;}
        }
        //if the movement was 'up' decrease the counter
        else{i=i-1;if(i<0){i=0;}}
        //HIDE ALL ELEMENTS
        document.getElementById("firstSlide").style.display= 'none';
        document.getElementById("secondSlide").style.display= 'none';
        document.getElementById("thirdSlide").style.display= 'none';
        //DISPLAY ONLY THE APPROPRIATE ONE
        if(i==0) {updateClouds(); document.getElementById("firstSlide").style.display= 'block'; }
        if(i==1) {document.getElementById("secondSlide").style.display= 'block';}
        if(i==2) {document.getElementById("thirdSlide").style.display= 'block';}
    }
});

// search the CSSOM for a specific -webkit-keyframe rule
function findKeyframesRule(rule)
    {
        // gather all stylesheets into an array
        var ss = document.styleSheets;

        // loop through the stylesheets
        for (var i = 0; i < ss.length; ++i) {

            // loop through all the rules
            for (var j = 0; j < ss[i].cssRules.length; ++j) {

                // find the -webkit-keyframe rule whose name matches our passed over parameter and return that rule
                if (ss[i].cssRules[j].name == rule)
                    return ss[i].cssRules[j];
            }
        }
        // rule not found
        return null;
    }
    //update all the clouds
    function updateClouds()
    {
        //iterate over all the clouds
        for(z=0;z<4;z++)
        {
            // find our -webkit-keyframe rule
            var keyframes = findKeyframesRule("cloudFloating"+z);
            if(keyframes!=null)
            {
                //alert("keyframeFound!");
                // delete all rules from the animation
                for(j=0;j<=100;j++){
                    keyframes.deleteRule(j+"%");
                }
                //alert("deletedAllRules!");
                //determine if large or small cloud
                cloudSize=55;
                if(z==0 || z==2){cloudSize=70}
                //calculate new rules
                //at 0 percent always initial positions
                var initialPosition= initialPositions[z];
                //if(initialPosition<0){initialPosition=100;}
                //alert("gotInitialPosition!");
                keyframes.appendRule("0% { left:"+ initialPosition+"vw;}");
                //alert("0% { left:"+ initialPosition+"vw;}");
                //calculate the percantage at which it should disappear -- formula (INITIALPOSITION+CLOUDSIZE)/100+CLOUDSIZE
                var percent= (((initialPosition+cloudSize)*1.0)/ (100+cloudSize))*100;
                if(percent>=4){percent=Math.floor(percent)-4;}
                else{percent=Math.floor(percent);}
                //alert("percent: "+percent)
                keyframes.appendRule(percent+"%{left:-"+cloudSize+"vw; opacity: 100%;-webkit-opacity: 1; }");
                //alert(percent+"%{left:-"+cloudSize+"vw; opacity: 100%;}")
                percent=percent+1;
                keyframes.appendRule(percent+"%{left:-"+cloudSize+"vw; opacity: 0%;-webkit-opacity: 0;}");
                //alert(percent+"%{left:-"+cloudSize+"vw; opacity: 0%;}")
                percent=percent+1;
                keyframes.appendRule(percent+"%{left: 100vw;opacity: 0%;-webkit-opacity: 0;}");
               // alert(percent+"%{left: 100vw;opacity: 0%;}")
                percent=percent+1;
                keyframes.appendRule(percent+"%{left: 100vw;opacity: 100%;-webkit-opacity: 1;}");
                keyframes.appendRule("100%{ left:"+ initialPosition+"vw;}");
                //alert(percent+"%{left: 100vw;opacity: 100%;}")
            }
        }
    }


// Tune deltaMin according to your needs. Near 0 it will almost
// always trigger, with a big value it can never trigger.
function detectSwipe(id, func, deltaMin = 85) {
  const swipe_det = {
    sX: 0,
    sY: 0,
    eX: 0,
    eY: 0
  }
  // Directions enumeration
  const directions = Object.freeze({
    UP: 'up',
    DOWN: 'down',
    RIGHT: 'right',
    LEFT: 'left'
  })
  let direction = null
  const el = document.getElementById(id)
  el.addEventListener('touchstart', function(e) {
    const t = e.touches[0]
    swipe_det.sX = t.screenX
    swipe_det.sY = t.screenY
  }, false)
  el.addEventListener('touchmove', function(e) {
    // Prevent default will stop user from scrolling, use with care
    // e.preventDefault();
    const t = e.touches[0]
    swipe_det.eX = t.screenX
    swipe_det.eY = t.screenY
  }, false)
  el.addEventListener('touchend', function(e) {
    const deltaX = swipe_det.eX - swipe_det.sX
    const deltaY = swipe_det.eY - swipe_det.sY
    // Min swipe distance, you could use absolute value rather
    // than square. It just felt better for personnal use
    if (deltaX ** 2 + deltaY ** 2 < deltaMin ** 2) return
    // horizontal
    if (deltaY === 0 || Math.abs(deltaX / deltaY) > 1)
      direction = deltaX > 0 ? directions.RIGHT : directions.LEFT
    else // vertical
      direction = deltaY > 0 ? directions.UP : directions.DOWN

    if (direction && typeof func === 'function') func(el, direction)

    direction = null
  }, false)
}