function clearSearch() {
  $('#searchText').val('');
  document.searchForm.submit();
}

function checkLoginSubmit(e) {
  var keynum;
  var keychar;

  if (window.event) {
    keynum = e.keyCode
  } else if(e.which)  {
    keynum = e.which
  }
  keychar = String.fromCharCode(keynum)
  if (keychar == "\r") {
    document.forms['login'].submit();
  }
}

function checkSearchSubmit(e, object) {
  var keynum;
  var keychar;

  if (window.event) {
    keynum = e.keyCode
  } else if(e.which)  {
    keynum = e.which
  }
  keychar = String.fromCharCode(keynum)
  if (keychar == "\r") {
    document.forms['searchForm'].submit();
  }
}
