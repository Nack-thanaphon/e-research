
var enableCache = true;
var jsCache = new Array();

var dynamicContent_ajaxObjects = new Array();

function ajax_showContent(divId, ajaxIndex, url) {
	var targetObj = document.getElementById(divId);
	targetObj.innerHTML = dynamicContent_ajaxObjects[ajaxIndex].response;
	if (enableCache) {
		jsCache[url] = dynamicContent_ajaxObjects[ajaxIndex].response;
	}
	dynamicContent_ajaxObjects[ajaxIndex] = false;

	ajax_parseJs(targetObj)
}

function ajax_loadContent(divId, url, aid, afile_path, contentid) {
	if (enableCache && jsCache[url]) {
		document.getElementById(divId).innerHTML = jsCache[url];
		return;
	}

	var ajaxIndex = dynamicContent_ajaxObjects.length;

	var html = '';

	url = url + '?c_file_path=' + afile_path + '&c_id=' + aid + '&content_id=' + contentid;
	document.getElementById(divId).innerHTML = html;
	dynamicContent_ajaxObjects[ajaxIndex] = new sack();
	dynamicContent_ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
	dynamicContent_ajaxObjects[ajaxIndex].onCompletion = function () { ajax_showContent(divId, ajaxIndex, url); };	// Specify function that will be executed after file has been found
	dynamicContent_ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function	

}

function ajax_parseJs(inputObj) {
	var jsTags = inputObj.getElementsByTagName('SCRIPT');
	for (var no = 0; no < jsTags.length; no++) {
		eval(jsTags[no].innerHTML);
	}
}