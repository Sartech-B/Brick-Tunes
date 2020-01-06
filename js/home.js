var playing = 0;
var playing_type = -1;
var viewed = [];
var types = ['top', 'search'];

setTimeout(function(){
	load_music();
}, 150);

setTimeout(function(){
	security_check();
}, 100);

function security_check(){
	req("footer-check", "./libs/scripts/logout_check.php");
	setTimeout(function(){
		// security_check();
	}, 100);
}

function load_music(){
	var element_class = $("#main-container").attr('class');
	req("main-container", "./libs/scripts/load_music.php?list="+element_class);
}

function load_mix(){
	var element_class = $("#mix-container").attr('class');
	req("mix-container", "./libs/scripts/load_mix.php?type="+element_class); 
}

function overlay_hover(_type, _id){
	var type = types[_type];
	var id = _id.replace(type+"-", "");
	$("#"+type+"-overlay-"+id).fadeIn(255);
}

function overlay_leave(_type, _id){
	var type = types[_type];
	var id = _id.replace(type+"-", "");
	if(playing!=id || playing_type!=_type){
		$("#"+type+"-overlay-"+id).fadeOut(255);
	}
}

function music_click(_type, _id){
	var type = types[_type];
	var id = _id.replace(type+"-", "");
	if(playing==id && playing_type==_type){ // checking whether the trigger music is playing now
		playing = 0;
		playing_type = -1;
		$("#"+type+"-song-"+id).trigger("pause").prop("currentTime",0);
		$("#"+type+"-now-playing-"+id).fadeOut(255);
		$("#"+type+"-overlay-"+id).fadeOut(255);
		$("#playing-music").fadeOut(255);
		document.getElementById("span-"+type+"-"+id).innerHTML = null;
	}
	else{
		if(playing!=0 && playing_type!=-1){
			var tmp_type = types[playing_type];
			$("#"+tmp_type+"-song-"+playing).trigger("pause");
			$("#"+tmp_type+"-now-playing-"+playing).fadeOut(255);
			$("#"+tmp_type+"-overlay-"+playing).fadeOut(255);
			document.getElementById("span-"+tmp_type+"-"+playing).innerHTML = null;
		}
		playing = id;
		playing_type = _type;
		req("span-"+type+"-"+id, "./libs/scripts/advance_music.php?id=song-"+id+"&type="+type);
		$("#"+type+"-song-"+id).trigger("play");
		$("#"+type+"-overlay-"+id).fadeIn(255);
		$("#"+type+"-now-playing-"+id).fadeIn(255);
		$("#playing-music").fadeIn(255);
		req("playing-music", "./libs/scripts/show_playing_music.php?id="+id+"&type="+_id+"&type_num_sec="+_type);
		var index = viewed.indexOf(id);
		if(index==-1) {
			req("view-container", "./libs/scripts/views.php?id="+id);
		}
	}
}

function stop_playing_music(){
	if(playing!=0 && playing_type!=-1) {
		var id = playing;
		var type = types[playing_type];
		$("#"+type+"-song-"+id).trigger("pause").prop("currentTime",0);
		$("#"+type+"-now-playing-"+id).fadeOut(255);
		$("#"+type+"-overlay-"+id).fadeOut(255);
		$("#playing-music").fadeOut(255);
		document.getElementById("span-"+type+"-"+id).innerHTML = null;
		playing = 0;
		playing_type = -1;
	}
}

function search(key, id){
	var type = types[playing_type];
	if(key!="") {
		if(type=="search") {
			stop_playing_music();
			setTimeout(function(){
				req("search-container", "./libs/scripts/search.php?key="+key);
			}, 250);
		}
		else {
			req("search-container", "./libs/scripts/search.php?key="+key);
		}
	}
	else {
		if(type=="search") {
			stop_playing_music();
			setTimeout(function(){
				document.getElementById("search-container").innerHTML = null;
			}, 250);
		}
		else {
			document.getElementById("search-container").innerHTML = null;
		}
	}
}

function req(element_id, path){
	data = new XMLHttpRequest();
	data.open("GET", path, false);
	data.send(null);
	document.getElementById(element_id).innerHTML = data.responseText;	
}