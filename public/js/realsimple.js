(function () {
   'use strict';
   // this function is strict...
}());

/* Overrides */
/*
(function(){
    var oldLog = console.log;
    console.log = function (message) {
        // DO MESSAGE HERE.
        oldLog.apply(console, arguments);
    };
})();
*/

//  Needed for escaping HTML
var hiddenEscapeTextArea = document.createElement('textarea');

String.prototype.escapeHTML = function() {
  var self = this;
  if ( typeof hiddenEscapeTextArea == "undefined" ) {
    var hiddenEscapeTextArea = document.createElement('textarea');
  }
  hiddenEscapeTextArea.textContent = self;
  return hiddenEscapeTextArea.innerHTML;
}; // end function escapeHTML

String.prototype.unescapeHTML = function() {
  var self = this;
  if ( typeof hiddenEscapeTextArea == "undefined" ) {
    var hiddenEscapeTextArea = document.createElement('textarea');
  }
  hiddenEscapeTextArea.innerHTML = self;
  return hiddenEscapeTextArea.textContent;
}; // end function unescapeHTML

/**
 * Encodifica en formato uri la cadena
 * Uso:
 *  var str = "Esta es una cadena";
 *  str = str.encodeUri();
 *  console.log( str );
 *  // Outputs "Esta%20es%20una%20cadena"
 */
String.prototype.encodeUri = function () {
  return encodeURIComponent( this );
}; // end encodeUri

/**
 * Decodifica la cadena a partir del formato uri
 * Uso:
 *  var str = "Esta%20es%20una%20cadena";
 *  str = str.decodeUri();
 *  console.log( str );
 *  // Outputs "Esta es una cadena";
 */
String.prototype.decodeUri = function () {
  return decodeURIComponent( this );
}; // end decodeUri

/**
 * Crea una cadena de expresión regular a partir de la cadena,
 * anteponiendo "\" a los caracteres especiales, "escapando"
 * la cadena
 * Uso:
 *  var str = "Hola!";
 *  str = str.escapeRegExp();
 *  console.log( str );
 *  // Outputs: "Hola\!"
 */
String.prototype.escapeRegExp = function () {
  return this.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}; // end function escapeRegExp

/**
 * Reemplaza todas las instancias de "find" por "replace"
 * en la cadena
 * Uso:
 *  str = "Hola me llamo $nombre, $nombre es mi nombre";
 *  str = str.replaceAll( "$nombre", "Luis" );
 *  console.log( str );
 *  // Outputs: "Hola me llamo Luis, Luis es mi nombre"
 */
String.prototype.replaceAll = function ( find, replace ) {
  return this.replace(new RegExp(find.escapeRegExp(), 'g'), replace);
}; // end function replaceAll

/**
 * Regresa "n" caracteres a la izquierda de la cadena
 * Uso:
 *  var str = "ABCDE";
 *  str = str.left(3);
 *  console.log( str );
 *  // Outputs: "ABC"
 */
String.prototype.left = function ( n ) {
	if (n <= 0) {
	    return "";
  }
	else if (n > this.length) {
	    return this;
  }
	else {
	    return this.substring(0,n);
  }
}; // end function left

/**
 * Devuelve "n" caracteres a la derecha de la cadena
 * Uso:
 *  var str = "ABCDE";
 *  str = str.right(3);
 *  console.log( str );
 *  // Outputs: "CDE"
 */
String.prototype.right = function ( n ) {
    if (n <= 0) {
       return "";
    }
    else if (n > this.length) {
       return this;
    }
    else {
       var iLen = this.length;
       return this.substring(iLen, iLen - n);
    }
}; // end function right

/**
 * Devuelve verdadero si la cadena contiene a "str"
 * Uso:
 *  var str = "Hola mundo!";
 *  var lookFor = "mundo";
 *  var inString = str.contrains(lookFor);
 *  console.log(inString);
 *  // Outputs: true
 */
String.prototype.contains = function ( str ) {
  return (
    this.indexOf( str ) > -1
  );
}; // end function contains

HTMLElement.prototype.hasClass = function ( className ) {
  var rgx = new RegExp('(\\s|^)' + className + '(\\s|$)');
  var match = this.className.match( rgx );
  return ( match !== null );
};

HTMLElement.prototype.addClass = function( className ) {
    if (!this.hasClass(className)) {
      this.className += " " + className;
    }
};

HTMLElement.prototype.removeClass = function( className ) {
  if (this.hasClass(className)) {
    var reg = new RegExp('(\\s|^)' + className + '(\\s|$)');
    this.className = this.className.replace(reg,' ');
  }
};

Math.isNumeric = function  ( n ) {
  return !isNaN( parseFloat( n ) ) && isFinite( n );
}; // end function isNumeric

Array.prototype.encodeUri = function () {
  var str = JSON.stringify(this);
  return encodeURIComponent(str);
}; // end Object encodeUri

Array.prototype.where = function ( item, value ) {
  var self = this;
  var result = [];
  var fn = function ( obj, index, arr ) {
    if ( obj[item] == value ) {
      result.push( obj );
    } // end if value
  }; // end function fn

  self.forEach( fn );
  return result;
}; // end function where

Array.prototype.andWhere = function ( item, value ) {
  var self = this;
  var result = [];
  var fn = function ( obj, index, arr ) {
    if ( obj[item] == value ) {
      result.push( obj );
    } // end if value
  }; // end function fn

  self.forEach( fn );
  return result;
}; // end function where

Array.prototype.first = function () {
	return this[0];
}; // end function first

Array.prototype.like = function ( item, value ) {
  var self = this;
  var result = [];
  var fn = function ( obj, index, arr ) {
    if ( String( obj[item] ).contains( value ) ) {
      result.push( obj );
    } // end if value
  }; // end function fn

  self.forEach( fn );
  return result;
}; // end function where

Array.prototype.andLike = function ( item, value ) {
  var self = this;
  var result = [];
  var fn = function ( obj, index, arr ) {
    if ( String( obj[item] ).contains( value ) ) {
      result.push( obj );
    } // end if value
  }; // end function fn

  self.forEach( fn );
  return result;
}; // end function where

Array.prototype.indexOf = function( item, value ) {
  var self = this;
  var result = -1;
  var fn = function ( elem, index, arr ) {
    if ( elem[item] == value ) {
      result = index;
    } // end if value
  }; // end function fn

  self.forEach( fn );
  return result;
}; // end function where

Array.prototype.contains = function( value ) {
  var self = this;
  var result = false;
  var fn = function ( elem, index, self ) {
    if ( elem == value ) {
      result = true;
    } // end if value
  }; // end function fn

  self.forEach( fn );
  return result;
}; // end function where

/**
 * The function expects parameters index and value
 * Usage:
	 var arr = ["a", "b", "c"];
	 var fn = function ( i, val ) {
		 console.log(i + " => " + val);
	 };
	 arr.forEach( fn );
 */
 if ( typeof Array.prototype.forEach == "undefined" ) {
   var self = this;
   Array.prototype.forEach = function( fn ) {
   	var i;
   	var len;
   	len = self.length;

   	for ( i = 0; i < len; i++ ) {
   		fn ( self[i], i, self );
   	} // end for
   }; // end Array.forEach
 } // end if Array forEach undefined

 /**
  * Watch variables and report changes
  */
 function Watcher() {
   var self = this;
   this.collection = [];

   this.add = function( name, fn ) {
     var item = {};
     item.name = name;
     item.currentValue = globals.returnValue( name );
     item.function = fn;
     this.collection.push( item );
   } // end function add

   // The tick event
   var fn = function() {
     var fnEach = function( item, index, arr ) {
       var val = globals.returnValue( item.name );

       if ( val != item.currentValue ) {
         // And here we reverse dom
         item.function( item.name );
         item.currentValue = val;
       }
     }; // end fnEach
     self.collection.forEach( fnEach );
   }; // end fn

   setInterval( fn, 200 );
 } // end function watcher

 // The global variable
 var watcher = new Watcher();

 /**
  * Contains functions for globals variable management
  */
 function Globals() {
   /**
    * Function assignValue
    * @param bindable The string of the bindable object
    * @param value The value to assign
    */
    this.assignValue = function( bindable, value ) {
     if ( typeof value == 'string' ) {
       value = "'" + value + "'";
     } // end if string
     var code = bindable + " = " + value + ";";

     var f = new Function( code );
     f();
   }; // end function assignValue

   /**
    * Returns the value for a expression
    * @param bindable The expression to evaluate
    */
   this.returnValue = function ( bindable ) {
     var self = this;
     var parts = bindable.split('.');

     if ( parts.length > 0 ) {
       if (  self.evalUndefined( parts[0] ) ) {
         return null;
       } else if ( self.evalUndefined( bindable ) ) {
         return null;
       } else {
         var code = "return " + bindable + ";";
         var f = new Function( code );
         return f();
       } // end if then else
     } else if ( self.evalUndefined( bindable ) ) {
       return null;
     } // else if not > 0
   }; // end function returnValue

   /**
    * Evaluates if an expression is undefined
    * @param bindable The expression to evaluate
    */
   this.evalUndefined = function ( bindable ) {
     var code = "return ( typeof " + bindable + " == 'undefined' );";
     var f = new Function( code );
     return f();
   }; // end function evalUndefined;

 } // end function Globals

 // The global variable
 var globals = new Globals();

 /**
  * Handles the dom
  */
  function Dom() {

    var self = this;
    this.get = function ( id ) {
      return document.getElementById(id);
    };

   	this.getInputsValues = function () {

   		var allInputs = document.getElementsByTagName('input');
   		var allSelects = document.getElementsByTagName('select');
   		var result = {};

   		var fn = function ( val, i, arr ) {
   			result[val.id] = val.value;
   		};

   		var fnSelects = function( val, i, arr) {
   			var selectedValue = val.options[val.options.selectedIndex].value;
   			result[val.id] = selectedValue;
   		};

   		allInputs.forEach( fn );
   		allSelects.forEach( fn );
   		return result;
   	};

    this.validateInputs = function () {

    	var inputs = document.querySelectorAll('input');
    	var isValid = true;
    	var msg = "";

    	var forEachInput = function ( value, index, arr ) {
    		isValid = isValid && value.checkValidity();
    		if ( value.checkValidity() == false ) {
    			var title = value.getAttribute('title');
    			if ( title !== null ) {
    				msg += title + '\r\n';
    			}
    		}
    	};

    	Array.prototype.forEach.call(inputs, forEachInput);

    	if ( !isValid ) {
    		dlg.showAlert(
    			msg,
    			"Error",
    			"Ok"
    		);
    	} // end if isNotValid
    	return isValid;
    }; // end function validateInputs

 	this.getSelect = function(name, data, val, text) {

 		var sel = document.createElement('select');
 		sel.id = name;
 		sel.name = name;

 		var fn = function ( v, i, a ) {
 			var opt = document.createElement('option');
 			opt.value = v[val];
 			opt.innerHTML = v[text];
 			sel.appendChild(opt);
 		};

 		data.forEach( fn );
 		return sel;
 	};

  this.populateSelect = function ( select, data, valueField, textField ) {
    var forEachItem = function ( item, key, arr ) {
 			var option = document.createElement('option');
 			option.value = item[valueField];
 			option.innerHTML = item[textField];
 			select.appendChild(option);
 		};

 		data.forEach( forEachItem );
  }; // end function populateSelect

   this.create = function ( nodeName ) {
     var self = this;
     var element = document.createElement( nodeName );

     if ( typeof self.config.options.useMVC != "undefined" ) {
       if ( self.config.options.useMVC ) {
         self.setMVCListener ( element );
       } // end if useMVC true
     } // end if useMVC defined
   } // end create

   /**
    * This function will set a listener onchange of the value for the input
    */
   this.setMVCListener = function ( input ) {
     /**
      * This function will be set to inputs
      */
     var onChange = function() {
       var self = this;
       var bindable = self.getAttribute('data-bind');
       if ( bindable !== null ) {

         var nodeName = self.nodeName.toLowerCase();
         var type = self.getAttribute('type');
         var val = null;
         if ( nodeName == 'input' && type == 'checkbox' ) {
           val = self.checked;
         } else if ( nodeName == 'select' ) {
           val = self.value;
         } else {
           val = self.value;
         }

         globals.assignValue( bindable, val );

         var bindedElements = document.querySelectorAll("[data-bind='" + bindable + "']");
         var len = bindedElements.length;
         var i;
         var property;

         var forEachBinded = function ( element, key, arr ) {
           nodeName = element.nodeName.toLowerCase();
           if ( nodeName == 'select' ) {
             element.value = globals.returnValue( bindable );
           } else {
             element.innerHTML = globals.returnValue( bindable );
           } // end if nodeName select
         } // end forEachBinded

         Array.prototype.forEach.call(bindedElements, forEachBinded);

       } // end if bindable
     } // end onChange

     switch ( input.nodeName.toLowerCase() ) {
       case 'input':
       var type = input.getAttribute('type');
         switch (type) {
           case 'text':
               input.addEventListener('keyup', onChange);
               input.addEventListener('blur', onChange);
             break;
           case 'email':
               input.addEventListener('keyup', onChange);
               input.addEventListener('blur', onChange);
             break;
           case 'password':
               input.addEventListener('keyup', onChange);
               input.addEventListener('blur', onChange);
             break;
           case 'date':
               input.addEventListener('change', onChange);
               input.addEventListener('blur', onChange);
             break;
           case 'time':
               input.addEventListener('change', onChange);
               input.addEventListener('blur', onChange);
             break;
           case 'checkbox':
               input.addEventListener('change', onChange);
             break;
           case 'radio':
               input.addEventListener('change', onChange);
           break;
           default:

         } // end switch
         break;
       case 'textarea':
         input.addEventListener('keyup', onChange);
         input.addEventListener('blur', onChange);
         break;
       case 'select':
         input.addEventListener('change', onChange);
         break;
       default:
        // No input
     } // end switch
   }; // end end setMVCListener

   this.bindData = function () {

     var self = this;
     var forEachInput = function(input, key, arr) {
       dom.setMVCListener( input );
     }; // end function forEachInput

     var inputs = document.querySelectorAll('input, textarea, select');
     Array.prototype.forEach.call(inputs, forEachInput);

     var selectElements = document.querySelectorAll("select[data-source]");

     var forEachSelect = function ( select, key, arr ) {
       var valueField = select.getAttribute('data-value-field');
       var textField = select.getAttribute('data-text-field');
       var dataSource = select.getAttribute('data-source');

       dataSource = (window[dataSource]);

       self.populateSelect( select, dataSource, valueField, textField );
     }; // end forEachSelect

     Array.prototype.forEach.call(selectElements, forEachSelect);

     var bindedElements = document.querySelectorAll("[data-bind]");
     var len = bindedElements.length;
     var i;
     var property;

     var forEachBinded = function ( element, key, arr ) {
       nodeName = element.nodeName.toLowerCase();
       var bindable = element.getAttribute('data-bind');

       if ( globals.evalUndefined( bindable ) ) {
         if ( nodeName == "select" || nodeName == "input" || nodeName == "textarea" ) {
           element.value = globals.returnValue( bindable );
         } else {
           element.innerHTML = globals.returnValue( bindable );
         } // end if nodeName select
       } // end if not undefined
     } // end forEachBinded

     Array.prototype.forEach.call(bindedElements, forEachBinded);
   }; // end function bindData

   this.bind = function( variableName ) {
     var bindedElements = document.querySelectorAll("[data-bind]");
     var len = bindedElements.length;
     var i;
     var property;

     var forEachBinded = function ( element, key, arr ) {
       nodeName = element.nodeName.toLowerCase();
       var bindable = element.getAttribute('data-bind');
       if ( bindable.startsWith( variableName ) ) {
         if ( !globals.evalUndefined( bindable ) ) {
           if ( nodeName == "select" || nodeName == "input" || nodeName == "textarea" ) {
             element.value = globals.returnValue( bindable );
           } else {
             element.innerHTML = globals.returnValue( bindable );
           } // end if nodeName select
         } // end if not undefined
       } // end if bindable = variableName
     } // end forEachBinded

     Array.prototype.forEach.call(bindedElements, forEachBinded);
   }; // end function bind

   /**
    * Adds a model to the current memory stack
    */
   this.addModel = function( name, data ) {
     var fn = function( name ) {
       console.log( name + " has changed" );
       // self.bind( name );
       // here we look for data-bind and changed them
     }; // end fn
     if ( data.constructor != 'RSObject' ) {
       console.log(data.constructor);
       data = new RSObject(data);
     } // end if
     var fnEach = function(value, key, obj) {
       watcher.add( name + "." + key, fn);
     }; // end fnEach
     data.forEach(fnEach);
     window[name] = data;
     console.log(name);
     console.log(window[name]);
   }; // end function addModel

   this.onReady = function( fn ) {

     var readyStateChange = function() {
       if ( document.readyState === "complete" ) {
         fn();
       }
     }; // end function readyStateChange

     if ( document.readyState === "complete" ) {
       fn();
     } else {
       if (document.addEventListener) {
         document.addEventListener("DOMContentLoaded", fn, false);
         document.addEventListener("load", fn, false);
       } else {
         document.attachEvent("onreadystatechange", readyStateChange);
         window.attachEvent("onload", fn);
       }// end if document.addEventListener
     } // en di then else document.readyState complete
   }; // end onReady

   this.addTabEvents = function() {
     console.log( 'hit addTabEvents' );

     var onClick = function() {
       var tabs = document.getElementsByClassName("tab");
       var forEachTab = function ( value, index, arr ) {
         value.removeClass( "active" );
       }; // end forEachTab

       Array.prototype.forEach.call(tabs, forEachTab);

       var tabName = this.getAttribute("data-set-tab");
       var tab = dom.get( tabName );
       tab.addClass( "active" );
     };

     var tabHeaders = document.getElementsByClassName("tab-header");

     var forEachHeader = function ( value, index, arr ) {
       value.addEventListener(
         "click",
         onClick
       );
     };

     Array.prototype.forEach.call(tabHeaders, forEachHeader);

   }; // end addTabEvents
 } // end function Dom

 var dom = new Dom();

 function RSObject( obj ) {

   if ( obj === null ) {
     obj = {};
   } // end if obj is null

   var self = this;
   var keys = {};
   var fnEach = function ( elem, index, arr ) {
     self[elem] = obj[elem];
   };

   if ( typeof obj != "undefined" ) {
     keys = Object.keys( obj );
     keys.forEach( fnEach );
   }
 }

 /**
  * The function expects parameters key and value
  * Usage:
 		var fn = function ( k, v ) {
 		 console.log( k + " => " + v );
 		};
 		var obj={"a":"A","b":"B","c":"C"};
 		obj.forEach( fn );
  */
 RSObject.prototype.forEach = function ( fn ) {
   var self = this;
 	 var keys = Object.keys(self);
 	 var i;
 	 var len = keys.length;

 	 for ( i = 0; i < len; i ++ ) {
     fn ( self[keys[i]], keys[i], self );
   } // end for
 }; // end function Object.forEach

 RSObject.prototype.encodeUri = function () {
   var str = JSON.stringify(this);
   return encodeURIComponent(str);
 }; // end Object encodeUri

 RSObject.prototype.decodeUri = function () {
   str = JSON.stringify(this);
   return decodeURIComponent(str);
 }; // end Object encodeUri

 RSObject.prototype.serialize = function () {
   var result = [];
   var current = "";

   var fn = function ( value, key, obj) {

     if ( typeof value == "function") {
       return;
     } else if ( typeof value == "object" ) {
       current = key;
       value.forEach( fn );
       current = "";
     } else {
       if ( current !== "" ) {
         var item = current + "[" + key + "]=" + value;
         result.push( item );
       } else {
         result.push( key + "=" + value );
       }
     } // end if value is object

   }; // end fn

   this.forEach( fn );

   return result.join( "&" );
 }; // end function serialize


function RS() {

  var self = this;
  this.config = null;
  var locationHashBusy = false;
  var storedHash = window.location.hash;

  var manageRoutes = function ( locationHash ) {
    locationHashBusy = true;
    if (
      self.config.routes !== 'undefined' &&
      self.config.routes !== null
    ) {
      locationHash = String( locationHash );
      locationHash = locationHash.replace( "#","" );
      var route = self.config.routes[locationHash];
      if ( typeof route != 'undefined' ) {
        locationHash = route
      } // end if route undefined

      var args = locationHash.split("/");
      if ( args.length >= 2 ) {
        var controller = args[0];
        var action = args[1];
        args.splice(0,2);
        console.log(controller);
        console.log(action);
        console.log(args);
        (window[controller][action]).apply(this,args);
      } else {
        console.error( 'La ruta o ubicacion debe tener al menos controlador/funcion' );
      } // end if then else args len >= 2
    } // end if config.routes
    locationHashBusy = false;
  }; // end function manageRoutes

  var hashChanged = function(locationHash) {
    console.log( locationHashBusy );
    if ( locationHashBusy == false ) {
      //  Here goes the routehandler
      manageRoutes(locationHash);
    } // end if not busy
  }; // end function hashChanged

  var hashChangedTick = function () {
    //console.log('tick');
    if ( window.location.hash != storedHash ) {
      storedHash = window.location.hash;
      hashChanged(storedHash);
    } // end if has != storedHash
  }; // end function hashChangedTick

  var setOnHashChange = function () {
    window.setInterval(
      hashChangedTick,
      300
    );
  }; // end function setOnHashChange

  // loads config.json into config
  this.readConfig = function( configUrl ) {
    if ( typeof http == "undefined" ) {
      var http = new Http();
    } // end if http is undefined

    var onSuccess = function ( response ) {

      if ( typeof response == "object" ) {
        self.config = response;
      } else {
        try {
          var jsonResponse = JSON.parse( response );
          response = jsonResponse;
        } catch ( ex ) {
          console.error("Config is not json or is unavailable");
          console.error(response);
          return;
        } // end try catch
      } // end if then else response is object

      //  Set the onhashchange function, if configured
      if ( typeof self.config != "undefined" ) {
        if ( typeof self.config.options.useRouting != "undefined" ) {
          if ( self.config.options.useRouting) {
            setOnHashChange();
          } // end if routing
        } // end if useRouting
        if ( typeof self.config.options.useMVC != "undefined" ) {
          if ( self.config.options.useMVC ) {
            dom.bindData();
          } // end is useMVC
        } // end if useMVC undefined
      } // end if self.config undefined
    }; // end onSuccess

    var onError = function ( statusText ) {
      console.error( statusText );
      alert( statusText );
    }; // end onError

    if ( typeof configUrl != "undefined" ) {
      http.get( configUrl, null, onSuccess, onError );
    } else {
      console.error("Configuration file undefined");
    } // end if configUrl undefined
  }; // end function readConfig

} // end class rs

var rs = new RS();

/*
 *  Controller
 */
function Controller() {

} // end function controller

/*
	::Dialog.js::
	Functions for modal dialogs
	Dependencies
		Cordova
*/
function Dialog() {

	this.showAlert = function (msg, title, buttonText) {
		if ( typeof navigator.notification == 'undefined' ) {
			alert(msg);
		} else {
			navigator.notification.alert(
				msg,  // message
				null,         // callback: Do nothing, just message
				title,            // title
				buttonText                  // buttonName
			);
		}
	};

	this.showConfirm = function (msg, onConfirm) {
		if ( navigator.notification === null ) {
			var result = confirm(msg);
			if ( result ) {
				onConfirm(1);
			} else {
				onConfirm(2);
			}
		} else {
			navigator.notification.confirm(
				msg, // message
				onConfirm,            // callback to invoke with index of button pressed
				'Confirmar',           // title
				['Si', 'No']         // buttonLabels
			);
		}
	};

} // end function Dialog

//	Global instance
var dlg = new Dialog();

/*::End::*/

/*
	::Local.js::
	Do Local Storage
	Dependencies
		None
*/
function Local() {

	this.set = function(itemKey, itemValue) {
		window.localStorage.setItem(
			itemKey,
			itemValue
		);
	};

	this.remove = function (itemKey) {
		window.localStorage.removeItem(
			itemKey
		);
	};

	this.get = function (itemKey) {
		if (window.localStorage.getItem(itemKey) === null) {
			console.log("Item " + itemKey + " do not exists");
			return null;
		}
		return window.localStorage.getItem(itemKey);
	};

	this.exists = function (itemKey) {
		return (window.localStorage.getItem(itemKey) !== null);
	};

	this.getAll = function () {
		var i = 0;
		var len = localStorage.length;
		var params = {};

		for ( i = 0; i < len; ++i ) {
			params[ localStorage.key(i) ] = localStorage.getItem( localStorage.key( i ) );
		}

		return params;
	};
}

var local = new Local();

/*::End::*/

/*
	::Geo.js::
	This script manages GeoPosition
	Dependencies
		Google maps api
		Dialog
		Local
*/

/*	Class for Geo Position */
/*
	This contains the structure data
*/
function GeoPosition() {
  this.Lat = 0;
  this.Lng = 0;
  this.LatLng = null;
  this.Geocode = [];
  this.StreetNumber = "";
  this.Route = "";
  this.SubLocality = "";
  this.City = "";
  this.State = "";
  this.Country = "";
  this.PostalCode = "";
  this.Address = "";
} // end class GeoPosition

function Geo() {

	this.onError = function(error) {
		dlg.showAlert('Hubo problemas obteniendo la posicion');
	};

	this.onSuccessGetPosition = function(position) {

		local.remove('currentGeoPosition');
		var geoPos = new GeoPosition();

		geoPos.Lat = parseFloat(position.coords.latitude);
		geoPos.Lng = parseFloat(position.coords.longitude);
		geoPos.LatLng = new google.maps.LatLng(geoPos.Lat, geoPos.Lng);

		this.coder = new google.maps.Geocoder();
		this.coder.geocode(
			{'latLng': geoPos.LatLng},
			function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {

					if (results[0]) {
						var arrAddress = results[0].address_components;
						var i = 0;
						for ( i = 0; i < arrAddress.length; i++ ) {
							switch ( arrAddress[i].types[0] ) {
								case "street_number":
									geoPos.StreetNumber = arrAddress[i].long_name;
								break;
								case "route":
									geoPos.Route = arrAddress[i].long_name;
								break;
								case "sublocality_level_1":
									geoPos.SubLocality = arrAddress[i].long_name;
								break;
								case "locality":
									geoPos.City = arrAddress[i].long_name;
								break;
								case "administrative_area_level_1":
									geoPos.State = arrAddress[i].long_name;
								break;
								case "country":
									geoPos.Country = arrAddress[i].long_name;
								break;
								case "postal_code":
									geoPos.PostalCode = arrAddress[i].long_name;
								break;
							}
						}

						geoPos.Address =
							geoPos.Route + ", " +
							geoPos.StreetNumber + ", " +
							geoPos.SubLocality + ", " +
							geoPos.City + ", " +
							geoPos.State + ", " +
							geoPos.Country;

						local.set('currentGeoPosition', JSON.stringify(geoPos));

						fn(geoPos);

					} else {
						dlg.showAlert("No results found", "Error", "Ok");
					}
				} else {
					dlg.showAlert("Geocoder failed due to: " + status, "Error", "Ok");
				}
			}
		);
	};

	this.getPositionAddress = function(callBack) {
		var fn = callBack;
    var options = { maximumAge: 0, timeout: 10000, enableHighAccuracy:true };
		navigator.geolocation.getCurrentPosition(
			this.onSuccessGetPosition,
			this.onError
		);
	};
} // end clas Geo

//	Global instance
var geo = new Geo();

function Input() {
  // Retrives the inputs
} // end class inputs

/*::End::*/

function Http() {

  var self = this;

  var headers = new RSObject();

  this.setHeader = function ( key, value ) {
    headers[key] = value;
  }; // end function setHeader

  this.getHeaders = function () {
    return headers;
  }; // end function getHeaders

  this.clearHeaders = function () {
    headers = {};
  }; // end function clearHeaders

  this.get = function ( url, data, onSuccess, onError ) {
    self.request( "GET", url, data, onSuccess, onError );
  }; // end function post

  this.post = function ( url, data, onSuccess, onError ) {
    self.request( "POST", url, data, onSuccess, onError );
  }; // end function post

  this.put = function ( url, data, onSuccess, onError ) {
    self.request( "PUT", url, data, onSuccess, onError );
  }; // end function post

  this.delete = function ( url, data, onSuccess, onError ) {
    self.request( "DELETE", url, data, onSuccess, onError );
  }; // end function post

  this.head = function ( url, data, onSuccess, onError ) {
    self.request( "HEAD", url, data, onSuccess, onError );
  }; // end function post

  this.request = function(method, url, data, onSuccess, onError) {

    //  Manage onError absence if any
    var localOnError = null;
    if ( typeof onError == "undefined" ) {
      localOnError = function ( statusText ) {
        console.error( statusText );
      }; // end function localOnError
    } else {
      localOnError = onError;
    }// end if then else undefined onError

    //  Function on statechange
    var fnStateChange = function() {
      if (xhttp.readyState == 4 ) {
        if ( xhttp.status == 200 ) {
          var response = xhttp.responseText;
          var jsonResponse;
          try {
            jsonResponse = JSON.parse( response );
            response = jsonResponse;
          } catch ( ex ) {
            // Nothing to do here
          } // end try catch
          onSuccess( response );
        } else {
          localOnError(xhttp);
        } // end if then else status 200
      } // end if readyState 4
    }; // enf function onreadystatechange

    //  The actual request
    var xhttp = new XMLHttpRequest();
    //  Set the function
    xhttp.onreadystatechange = fnStateChange;

    // The headers
    var setHeaders = function () {
      var fnEach = function ( value, key, obj ) {
        xhttp.setRequestHeader(key, value);
      }; // end function fn
      headers.forEach ( fnEach );
    }; // end function setHeaders

    // Vars to post
    var toPost = null;
    var isBinary = false;
    if ( typeof data != "undefined" ) {
      if ( data !== null ){
        if ( String(data.constructor).contains('function File()') ) {
          toPost = data;
          isBinary = true;
        } else {
          data = new RSObject(data);
          toPost = data.serialize();
        } // end if constructor is formdata
      } // end if data is null
    } // end function is undefined data

    // setup method & url
    if ( method == "GET" ) {
      if ( toPost !== "" ) {
        url = url + "?" + toPost;
      } // end function toPost
      xhttp.open(method, url, true);
      setHeaders();
      xhttp.send();
    } else {
      xhttp.open(method, url, true);
      if ( isBinary ) {
        setHeaders();
        xhttp.setRequestHeader("Content-type", "multipart/form-data; boundary=blob");
        console.log( xhttp );
        xhttp.send( toPost );
      } else {
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        setHeaders();
        xhttp.send( toPost );
      } // end if isBinary
    } // end if method = get
  }; // end function request
} // end class Http

var http = new Http();

/**
 *  Holds the function for Restful calls
 */
function Restful( resourceUrl ) {

  //  Reference to itself
  var self = this;

  //  This variable will hold the handler for get requests
  var responseHandler = null;

  //  To get the name of the function
  //  var myName = arguments.callee.toString().match(/function ([^\(]+)/)[1];

  //  The RegEx to identify parameters in the url
  //  We expect only one parameter in the format :id
  var uriParamRegex = /:\b[a-z]+/g;
  var match = uriParamRegex.exec(resourceUrl);

  //  If any parameter
  if ( typeof match != "undefined"  ) {
    //  If any parameter
    if ( match.length > 0 ) {
      //  Get the name of the parameter
      match = match[0];
      //  Strip the escape char
      var key = match.replace(":","");
      //  If the property is set in the object
      if ( typeof self[key] != "undefined" ) {
        //  Replace in the url
        resourceUrl = resourceUrl.replace(match, self[key]);
      } else {
        //  If not, strip from the url
        resourceUrl = resourceUrl.replace(match, "");
      } // end if then else key is defined
    } // end if match len > 0
  } // end if matchUndefined

  //  This private function will search the object
  //  Restful itself and returns their public properties
  //  ( no functions, only data )
  var setData = function () {
    var data = {};
    var fnEach = function ( key, index, arr ) {
      var value = self[key];
      if ( typeof value != "function" ) {
        data[key] = value;
      } // end if not function
    }; // end fnEach

    keys = Object.keys( self );
    keys.forEach( fnEach );
    return data;
  }; // end function setData

  //  This private function will create a collection
  //  of restful objects
  var createCollection = function ( response ) {

    var result = [];
    var fnEach = function ( item, index, arr ) {

      //  replace with window["Restful"](resourceUrl)
      var r = new window["Restful"](resourceUrl);
      //var toEval = "var x = new " + myName + "(\"" + resourceUrl + "\");";
      var fnEachItem = function ( value, key, obj ) {
        //toEval += "x." + key + " = \"" + value + "\"; ";
        r[key] = value;
      };
      item.forEach( fnEachItem );
      //toEval += " result.push(x);";
      //eval(toEval);
      result.push(r);
    };

    response.forEach ( fnEach );

    return result;
  }; // end function createCollection

  //  This private function will update the object itself
  //  from a http response properties
  var updateModel = function ( model ) {
    var fnEach = function ( value, key, obj ) {
      self[key] = value;
    }; // end function fnEach
    model.forEach( fnEach );
  }; // end function updateModel

  //  This is the function to trigger on GET requests
  //  It will handle the response, accordingly.
  //  We expect an array for getAll or query (no id specified)
  //  and an object for an id specified request
  var onGet = function ( response ) {
    if ( typeof response == "object" ) {
      if ( response instanceof Array ) {
        if ( typeof responseHandler != "undefined" ) {
          var collection = createCollection( response );
          responseHandler( collection );
          responseHandler = null;
        } // end if not responseHandler undefined
      } else {
        updateModel( response );
      }// end if response is array
    } else {
      console.error( "Response is neither Array nor Object." );
    } // end if response is object
  }; // end function onSuccess

  var onRequest = function () {
    console.log( "ok" );
  }; // end function onRequest

  /** Begin Public methods **/

  //  Perform a GET request
  this.get = function ( fnResponse ) {
    if ( typeof fnResponse != "undefined" ) {
      responseHandler = fnResponse;
    } // end if fnOnResponse undefined
    //  Data not necesary for get
    http.get( resourceUrl, null, onGet );
  }; // end function get

  //  Perform a POST request
  this.post = function (){
    var data = setData();
    http.post( resourceUrl, data, onRequest );
  };

  //  Perform a PUT request
  this.put = function (){
    var data = setData();
    http.put( resourceUrl, data, onRequest );
  };

  //  Perform a DELETE request
  this.delete = function (){
    var data = setData();
    http.delete( resourceUrl, data, onRequest );
  };

  //  Perform a HEAD request
  this.head = function () {
    var data = setData();
    http.head( resourceUrl, data, onRequest );
  };

} // end class restful



/*
	::View.js::
	For loading content dynamically
	Dependencies
		Jquery
*/
function View( id ) {

  var self = this;
  this.contentId = id;
  this.content = null;
  this.httpR = null;

  this.setContent = function () {
    if ( this.content == null ) {
      this.content = dom.get( this.contentId );
    } // end if content undefined
  }; // end if setContent

  this.setHttp = function () {
    if ( this.httpR == null ) {
      if ( typeof http != "undefined" ) {
        this.httpR = http;
      } else {
        this.httpR = new Http();
      } // end if then else is undefined http
    } // end if httpR undefined
  }; // end setHttp;

  this.setContent();
  this.setHttp();

  this.loadUrl = function ( url, data ) {

    if (
      typeof data == 'object'
      || data === null
    ) {
      data = new RSObject(data);
    }

    url = url + "?" + String((Math.random() * 10000000000000000) + 1);
    var onSuccess = function ( response ) {
      var template = String(response);
      template = self.populateTemplate( template, data );
      self.content.innerHTML = template;
      dom.bindData();
      //self.content.appendChild(document.createTextNode(template));
    }; // end function fn

    this.httpR.get( url, null, onSuccess );
  }; // end function load

  this.loadTemplate = function ( id, data ) {
    var html = dom.get( id ).innerHTML;
    self.loadHTML( html, data );
    dom.bindData();
  }; // end function loadTemplate

  this.loadHTML = function ( html, data ) {
    html = self.populateTemplate( html, data );
    self.content.innerHTML = html;
    dom.bindData();
  }; // end function load

  this.populateTemplate = function (template, data) {

    if ( typeof data != 'undefined' ) {
      if ( data !== null ) {
        var jsonString = JSON.stringify(data);
        jsonString = jsonString.encodeUri();
      	template = template.replaceAll( '$itemJson', jsonString );

        var fn = function ( value, key, obj ) {
          var find = "$" + key;
          template = template.replaceAll( find, value );
        }; // end function fn (forEach)

        data.forEach( fn );
      } // end if data not null
    } // end if data not undefined

    return template;
  }; // end function populateTemplate
} // end class View

/*::End::*/


/** DateHelper **/
function DateHelper() {
  this.getDateParts = function( date ) {

  	var dateObj = new Date();
  	if ( typeof date != "undefined" ) {
  		dateObj = new Date( date );
  	}

  	var month = dateObj.getUTCMonth() + 1; //months from 1-12
  	var day = dateObj.getUTCDate();
  	var year = dateObj.getUTCFullYear();
  	var hour = dateObj.getUTCHours();
  	var minutes = dateObj.getUTCMinutes();
  	var seconds = dateObj.getUTCSeconds();
  	month = "00" + month;
  	month = String(month).right(2);
  	day = "00" + day;
  	day = String(day).right(2);
  	hour = "00" + hour;
  	hour = String(hour).right(2);
  	minutes = "00" + minutes;
  	minutes = String(minutes).right(2);
  	seconds = "00" + seconds;
  	seconds = String(seconds).right(2);
  	var newTime = hour + ":" + minutes + ":" + seconds;
  	var newDate = year + "/" + month + "/" + day;

  	var dateParts = {};
  	dateParts.year = year;
  	dateParts.month = month;
  	dateParts.day = day;
  	dateParts.hour = hour;
  	dateParts.date = newDate;
  	dateParts.minutes = minutes;
  	dateParts.seconds = seconds;
  	dateParts.time = newTime;
  	return dateParts;
  };
} // end function DateHelper

var date = new DateHelper();

/**
 * Helper for cookies
 */
function cookieHelper() {

  this.set = function (name, value, days) {
    var date = new Date();
    date.setTime(
      date.getTime() + ( exdays*24*60*60*1000 )
    );
    var expiresAt =
      "expires=" + d.toUTCString();
    var cookie =
      name + "=" +
      value + "; expires=" +
      expiresAt;
    document.cookie = cookie;
}

function getCookie(name) {

    var name = name + "=";
    var cookies = document.cookie.split(';');

    var fnEach = function ( value, index, array ) {
      var cookie = value;
      while (cookie.charAt(0) == ' ') {
          cookie = cookie.substring(1);
      } // end while

      if (cookie.indexOf(name) == 0) {
          return
            cookie.substring(
              name.length,
              c.length
            );
      } // end if cookie name
    } // end function fnEach

    cookies.forEach( fnEach );
    return "";
}

function checkCookie() {
    var user = getCookie("username");
    if (user != "") {
        alert("Welcome again " + user);
    } else {
        user = prompt("Please enter your name:", "");
        if (user != "" && user != null) {
            setCookie("username", user, 365);
        }
    }
}
} // end function cookieHelper