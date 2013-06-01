var xmlHttp;

function GetXmlHttpObject() {
  var objXMLHttp=null
  if (window.XMLHttpRequest)
  {
    objXMLHttp=new XMLHttpRequest()
  }
  else if (window.ActiveXObject)
  {
    objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
  }

  if (objXMLHttp == null) {
    alert("Browser does not support HTTP Request");
  }

  var objXMLHttp2 = {};
  objXMLHttp2.open = function (method, url, sync) {
    objXMLHttp2.url = url;
    objXMLHttp.open(method, url, sync);
  }
  objXMLHttp2.send = function (xml) {
    objXMLHttp.send(xml);
  }
  objXMLHttp.onreadystatechange = function () {
    if (objXMLHttp.readyState == 4) {
    }
    if (objXMLHttp.readyState == 4) {
      if (objXMLHttp.responseText.match(/Maximum execution time of [0-9]* seconds exceeded/)) {
        alert('Przekroczono czas oczekiwania - '+objXMLHttp2.url);
      } else {    
        objXMLHttp2.readyState = objXMLHttp.readyState;
        objXMLHttp2.responseText = objXMLHttp.responseText;
        objXMLHttp2.responseXML = objXMLHttp.responseXML;
        objXMLHttp2.onreadystatechange();
      }
    }
  }

  return objXMLHttp2;
}

function getMaxXPosition(itemWidth) {
  var positionX = mouseX;
  var screenWidth = $(document).width();
  
  if (positionX > screenWidth - itemWidth) {
    positionX = screenWidth - itemWidth - 50;
  }
  return positionX;
}

function getParcelInfo(X, Y) {
  
   $.post("ajax/request.php", { 
    brand: 'static',
    requestClass: 'parcel',
    requestMethod: 'sGetSummary',
    X: X,
    Y: Y,
    loggedUserID: loggedUserID},
    function(html){
      $('#parcelPanel').css('left',getMaxXPosition(490)+'px');
      $('#parcelPanel').css('top',mouseY+'px');
      $('#parcelPanel > div:first').html(html);
      $('#parcelPanel').show();
    });
}

function getLifeFormInfo(species, ID) {
  $.post("ajax/request.php", { 
    brand: 'static',
    requestClass: species,
    requestMethod: 'sGetSummary',
    ID: ID,
    loggedUserID: loggedUserID},
    function(html){
      $('#lifeFormPanel').css('left',getMaxXPosition(490)+'px');
      $('#lifeFormPanel').css('top',mouseY+'px');
      $('#lifeFormPanel > div:first').html(html);
      $('#lifeFormPanel').show();
    });
}

function killLifeForm(ID) {
  $.post("ajax/request.php", { 
    brand: 'static',
    requestClass: 'lifeForm',
    requestMethod: 'sKill',
    ID: ID,
    loggedUserID: loggedUserID},
    function(html){
      $('#lifeFormPanel').hide();
    });
}

function pushLearnPattern(reaction) {
  
  var sendXML = '<?xml version="1.0" ?>';
  sendXML = sendXML + "<data>\n";
  
  /*
   * Pobierz wszystkie parametry do tablicy
   */
   $('learnPattern').each(function (i) {
     sendXML  = sendXML + '<s' + $(this).attr('name') + '>' + $(this).attr('value') + '</s' + $(this).attr('name') + '>' + "\n";
   });
  
   sendXML = sendXML + "</data>";
   
   $.post("ajax/request.php", { 
    brand: 'static',
    requestClass: 'lifeForm',
    requestMethod: 'pushLearnPattern',
    xml: sendXML,
    reaction: reaction,
    learnMode: $('learnMode').attr('value'),
    loggedUserID: loggedUserID},
    function(html){
      executeSimpleGet('lifeForm','sRenderLearnWindow','next',false,'#univPanel', 'modal');
    });
}

function executeSimpleGet(requestClass, requestMethod, ID, reload, output, mode) {
  $.post("ajax/request.php", { 
    brand: 'static',
    requestClass: requestClass,
    requestMethod: requestMethod,
    ID: ID,
    loggedUserID: loggedUserID},
    function(html){
      if (reload == true) {
        document.location.reload();
      }
      
      if (output != null) {
      
        if (mode ==  null) {
          mode = 'normal';
        }
      
        $(output + ' > div:first').html(html);
      
        switch (mode) {
          
          case 'normal':
            $(output).css('left',getMaxXPosition(500)+'px');
            $(output).css('top',mouseY+'px');
            $(output).show();
            break;
          
          case 'centered' :
            $(output).css('left',Math.round($(document).width() / 2) - 250 + 'px');
            $(output).css('top','200px');
            $(output).show();
            break;
          
          case 'modal':
            $(output).modal();
            $(output).show();
            $(output + ' img:first').bind('click', function (e) {
              $.modal.close();
            });
            break;
          
        }
      }
            
    });
  
}