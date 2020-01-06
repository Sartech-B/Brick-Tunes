var playing = [];

setTimeout(function(){
	load_mix();
}, 150);

function overlay_hover(id){
	$("#overlay-"+id).fadeIn(255);
}

function overlay_leave(id){
	var index = playing.indexOf(id);
	if(index==-1){
		$("#overlay-"+id).fadeOut(255);
	}
}

function load_mix(){
	var element_class = $("#mix-container").attr('class');
	req("mix-container", "./libs/scripts/load_mix.php?type="+element_class); 
}

function music_click(id){
	var index = playing.indexOf(id);
	if(index!=-1){
		playing.splice(index, 1); //remove index from array
		// $("#song-"+id).trigger("pause").prop("currentTime",0);
		req("span-"+id, "./libs/scripts/null.php");
		$("#now-playing-"+id).fadeOut(255);
		$("#overlay-"+id).fadeOut(255);
	}
	else{
		playing.push(id);
		req("span-"+id, "./libs/scripts/advance_mix.php?id="+id);
		$("#overlay-"+id).fadeIn(255);
		$("#now-playing-"+id).fadeIn(255);
		replay();
		//views add
	}
}

function replay(){
	var size_ar = playing.length;
	for(var i=0; i<size_ar; i++){
		req("span-"+playing[i], "./libs/scripts/advance_mix.php?id="+playing[i]);
		$("#song-"+playing[i]).trigger("play", {queue: false});
		setTimeout(function(){
			$("#song-"+playing[i]).trigger("pause", {queue: false}).prop("currentTime",0);
		}, 2000, {queue: false});
	}
	for(var i=0; i<size_ar; i++){
		$("#song-"+playing[i]).trigger("play", {queue: false});
	}
}

function req(element_id, path){
	data = new XMLHttpRequest();
	data.open("GET", path, false);
	data.send(null);
	document.getElementById(element_id).innerHTML = data.responseText;
}