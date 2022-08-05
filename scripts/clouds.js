// usage example
i=0;
//values for the clouds
//var initialPositions = new Array(60, 5, 70, 20);
var initialPositions = new Array(100, 90, 5, 80);
updateClouds();


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
			cloudSize=10;
			if(z==0 || z==2){cloudSize=10}
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

