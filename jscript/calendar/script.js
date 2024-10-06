var trimFormat = /(^\s*)|(\s*$)/gi;
var numFormat = /^\d*$/;
var emailFormat = /^[_\.\'0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/;

function none() {
}
function toInt(value) {
	return parseInt(value);
}
function getTokenInt(value, lim, at) {
    if (value=="" || lim=="" || at<=0) return 0;
    var temp = new String(value);
    for (var i=0; i<(at-1); i++) {
      if (temp.indexOf(lim) >= 0) {
        temp = temp.substring(temp.indexOf(lim)+1, temp.length);
      } else {
        return 0;
      }
	}
    if (temp.indexOf(lim) >= 0) return toInt(temp.substring(0, temp.indexOf(lim)));
    else return toInt(temp);
}
function getTokenStr(value, lim, at) {
    if (value=="" || lim=="" || at<=0) return "";
    var temp = new String(value);
    for (var i=0; i<(at-1); i++) {
      if (temp.indexOf(lim) >= 0) {
        temp = temp.substring(temp.indexOf(lim)+1, temp.length);
      } else {
        return "";
      }
    }
    if (temp.indexOf(lim) >= 0) return temp.substring(0, temp.indexOf(lim));
    else return temp;
}
function onEnter(me) {
    switch (event.keyCode) {
        case 13:  // carriage return
			me.form.submit();
			break;
        default: break;
    }
}
function trim(value) {
	return value.replace(trimFormat,"");
}
function isEmailFormat(value) {
	if (value == "" || value.search(emailFormat) == -1) {
		return false;
	}
	return true;
}
function isEmptyEmailFormat(value) {
	if (value.search(emailFormat) == -1) {
		return false;
	}
	return true;
}
function isNumFormat(value) {
	if (value == "" || value.search(numFormat) == -1) {
		return false;
	}
	return true;
}
function isEmptyNumFormat(value) {
	if (value.search(numFormat) == -1) {
		return false;
	}
	return true;
}
function invalidString(comb, err) {
	if (trim(comb.value)=="") {
		alert(err);
		comb.focus();
		return true;
	}
	return false;
}
function invalidNumber(comb, err) {
	if (isNumFormat(comb.value)==false) {
		alert(err);
		comb.focus();
		return true;
	}
	return false;
}
function invalidEmptyNumber(comb, err) {
	if (isEmptyNumFormat(comb.value)==false) {
		alert(err);
		comb.focus();
		return true;
	}
	return false;
}
function columnArrowClick(form, i) {
	form.order_by.value = i;
	form.submit();
	return false;
}
function columnCheckBoxClick(form) {
	var check_all = form.checkbox_head.checked;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && 
			(el.name == "deleteid" || el.name == "gis_id" || el.name == "data_id")) {
			el.checked = check_all;
		}
    }
}
function check(form) {
	var check_all = true;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && 
			(el.name == "deleteid" || el.name == "gis_id" || el.name == "data_id") && 
			(el.checked == false)) {
			check_all = false;
			break;
		}
    }
	form.checkbox_head.checked = check_all;
}
function checkAllFunction1(form) {
	var check_all = form.function1.checked;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && (el.name == "function") && (new String(el.value).indexOf("1")==0)) {
			el.checked = check_all;
		}
    }
}
function checkAllFunction2(form) {
	var check_all = form.function2.checked;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && (el.name == "function") && (new String(el.value).indexOf("2")==0)) {
			el.checked = check_all;
		}
    }
}
function checkAllFunction3(form) {
	var check_all = form.function3.checked;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && (el.name == "function") && (new String(el.value).indexOf("3")==0)) {
			el.checked = check_all;
		}
    }
}
function checkAllFunction4(form) {
	var check_all = form.function4.checked;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && (el.name == "function") && (new String(el.value).indexOf("4")==0)) {
			el.checked = check_all;
		}
    }
}
function checkFunction1(form) {
	var check_all = true;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && (el.name == "function") && (new String(el.value).indexOf("1")==0) && (el.checked == false)) {
			check_all = false;
			break;
		}
    }
	form.function1.checked = check_all;
}
function checkFunction2(form) {
	var check_all = true;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && (el.name == "function") && (new String(el.value).indexOf("2")==0) && (el.checked == false)) {
			check_all = false;
			break;
		}
    }
	form.function2.checked = check_all;
}
function checkFunction3(form) {
	var check_all = true;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && (el.name == "function") && (new String(el.value).indexOf("3")==0) && (el.checked == false)) {
			check_all = false;
			break;
		}
    }
	form.function3.checked = check_all;
}
function checkFunction4(form) {
	var check_all = true;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && (el.name == "function") && (new String(el.value).indexOf("4")==0) && (el.checked == false)) {
			check_all = false;
			break;
		}
    }
	form.function4.checked = check_all;
}
function deleteSelectedItems(form) {
    var count = 0;
    for (var i = 0; i < form.elements.length; i++) {
        var el = form.elements[i];
        if ((el.type == "checkbox") && (el.name == "deleteid") && el.checked) count++;
    }
    if (count == 0) {
        alert('ท่านยังไม่ได้เลือกรายการที่ต้องการจะลบ');
    } else {
		var msg = "ต้องการลบข้อมูลจำนวน " + count + " รายการ ใช่หรือไม่?";
		if (confirm(msg)) {
			form.mode.value = "delete_list";
			form.submit();
			return false;
		}
	}
	return false;
}
function buttonGoClick(form) {
	var page = form.go_page.value;
	if (isNumFormat(page)) {
		form.page_index.value = page;
		form.submit();
		return false;
	}
	alert('โปรดป้อนเลขหน้าที่ต้องการเป็นตัวเลข');
	return false;
}
function goToPage(form, url) {
	form.action = url;
	form.submit();
	return false;
}
function goToPageWithList(form, url, list) {
	for (var i=0; i<list.options.length; i++) {
		var option = list.options[i];
		option.selected = true;
	}
	form.action = url;
	form.submit();
	return false;
}
function submitWithMode(form, mode) {
	form.mode.value = mode;
	form.submit();
	return false;
}
function submitWithModeWithList(form, mode, list) {
	for (var i=0; i<list.options.length; i++) {
		var option = list.options[i];
		option.selected = true;
	}
	form.mode.value = mode;
	form.submit();
	return false;
}
function submitWithModeWith2List(form, mode, list1, list2) {
	for (var i=0; i<list1.options.length; i++) {
		var option1 = list1.options[i];
		option1.selected = true;
	}
	for (var i=0; i<list2.options.length; i++) {
		var option2 = list2.options[i];
		option2.selected = true;
	}
	form.mode.value = mode;
	form.submit();
	return false;
}
function submitWithModeWith3List(form, mode, list1, list2, list3) {
	for (var i=0; i<list1.options.length; i++) {
		var option1 = list1.options[i];
		option1.selected = true;
	}
	for (var i=0; i<list2.options.length; i++) {
		var option2 = list2.options[i];
		option2.selected = true;
	}
	for (var i=0; i<list3.options.length; i++) {
		var option3 = list3.options[i];
		option3.selected = true;
	}
	form.mode.value = mode;
	form.submit();
	return false;
}
function deleteItem(form) {
	if (confirm("ท่านต้องการที่จะลบรายการนี้ ใช่หรือไม่?")) {
		form.mode.value = "delete_item";
		form.submit();
		return false;
	}
	return false;
}
function moveListItem(from, to) {
	for (var i=0; i<from.options.length; i++) {
		var option = from.options[i];
		if (option.selected) {
			from.options.remove(i);
			to.options.add(option);
			option.selected = false;
			i=-1;
		}
	}
	from.focus();
}
function submitWithOrderBy(form, order) {
	form.order_by.value = order;
	form.submit();
	return false;
}
function deleteNode(form) {
	if (myTree.getActiveElement() == false) {
		alert("โปรดคลิกที่โหนดที่ต้องการจะลบ");
		return false;
	}
	if (myTree.getActiveElement().dataContainer == 1) {
		alert("ผู้ใช้งานไม่สามารถทำการลบ root node ได้");
		return false;
	}
	if (confirm("โปรดยืนยันการลบข้อมูล")) {
		form.mode.value = "delete_node";
		form.id.value = myTree.getActiveElement().dataContainer;
		form.submit();
		return false;
	}
	return false;
}
function addStrategyNode(form) {
	if (myTree.getActiveElement() == false) {
		alert("โปรดคลิกที่โหนดที่ต้องการจะเพิ่มรายการ");
		return false;
	}
	var id = myTree.getActiveElement().dataContainer;
	window.open(
		'strategy_dialog.jsp?mode=add&id=' + id, 'addStrategyNode',
		'toolbar=0,location=0,directories=0,menuBar=0,scrollbars=1,resizable=0,status=0,alwaysRaised=0,' +
		'left=100,top=200,width=620,height=200');
	return false;
}
function editStrategyNode(form) {
	if (myTree.getActiveElement() == false) {
		alert("โปรดคลิกที่โหนดที่ต้องการจะแก้ไขรายการ");
		return false;
	}
	if (myTree.getActiveElement().dataContainer == 1) {
		alert("ผู้ใช้งานไม่สามารถทำการแก้ไข root node ได้");
		return false;
	}
	var id = myTree.getActiveElement().dataContainer;
	window.open(
		'strategy_dialog.jsp?mode=edit&id=' + id, 'editStrategyNode',
		'toolbar=0,location=0,directories=0,menuBar=0,scrollbars=1,resizable=0,status=0,alwaysRaised=0,' +
		'left=100,top=185,width=620,height=230');
	return false;
}
function addDivisionNode(form) {
	if (myTree.getActiveElement() == false) {
		alert("โปรดคลิกที่โหนดที่ต้องการจะเพิ่มรายการ");
		return false;
	}
	var level = myTree.getActiveElement()._level;
	if (level >= 4) {
		alert("ผู้ใช้งานไม่สามารถทำการเพิ่ม node ใน level นี้ได้");
		return false;
	}
	var id = myTree.getActiveElement().dataContainer;
	window.open(
		'division_dialog.jsp?mode=add&id=' + id + '&level=' + level, 'addDivisionNode',
		'toolbar=0,location=0,directories=0,menuBar=0,scrollbars=1,resizable=0,status=0,alwaysRaised=0,' +
		'left=100,top=230,width=620,height=140');
	return false;
}
function editDivisionNode(form) {
	if (myTree.getActiveElement() == false) {
		alert("โปรดคลิกที่โหนดที่ต้องการจะแก้ไขรายการ");
		return false;
	}
	if (myTree.getActiveElement().dataContainer == 1) {
		alert("ผู้ใช้งานไม่สามารถทำการแก้ไข root node ได้");
		return false;
	}
	var id = myTree.getActiveElement().dataContainer;
	window.open(
		'division_dialog.jsp?mode=edit&id=' + id, 'editDivisionNode',
		'toolbar=0,location=0,directories=0,menuBar=0,scrollbars=1,resizable=0,status=0,alwaysRaised=0,' +
		'left=100,top=215,width=620,height=170');
	return false;
}
function lookupPage(form, url, window_name, w, h) {
	var left, top;
	left = (800 - w) / 2;
	top = (600 - h) / 2;
	window.open(
		url, window_name,
		'toolbar=0,location=0,directories=0,menuBar=0,scrollbars=1,resizable=0,status=0,alwaysRaised=0,' +
		'left=' + left + ',top=' + top + ',width=' + w + ',height=' + h);
	return false;
}
function getFileExtName(fileName) {
	if (fileName == "") return "";
	return fileName.substring(fileName.lastIndexOf(".")+1, fileName.length);
}
