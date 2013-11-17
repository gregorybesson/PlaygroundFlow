/**
 * Copyright (C) 2013 - Playground
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * @namespace reference to Playground.Util object
 */

/** Util Object
 * @class
 * @name PG.Util
 */
var pgutil = { 
    
    /** 
     * Check if a variable is null
     * @function 
     * 
     * @name PG.Util.not_null
     * 
     * @param {Object} object DOM object
     * @return {boolean} bool true or false  
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.not_null( Object | String | ... )
     * 
     * @since version 1.0.0
     */
    not_null: function (obj)
    {
        'use strict';
        
        return ((obj !== null) ? ((typeof(obj) !== 'undefined') ? ((obj !== '') ? true : false) : false) : false);
    },
    
    /** 
     * Check if given variable points : {x:x, y:y} are into box {x:x, y:y, width:width, height:height}
     * @function 
     * 
     * @name PG.Util.pointOnBox
     * 
     * @param {Object} point (x, y)
     * @param {Object} box (x, y, width, height)
     * @return {boolean} bool true or false  
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.pointOnBox( {x:x, y:y}, {x:x, y:y, width:width, height:height} )
     * 
     * @since version 1.0.0
     */
    pointOnBox: function (point, box)
    {
        'use strict';
        var bool = false;
        
        if(point.x > box.x && point.x < parseInt(box.x + box.width, 10)) {
            if(point.y > box.y && point.y < parseInt(box.y + box.height, 10)) {
                bool = true;
            }
        }
        
        return bool;
    },
    
    /** 
     * Return object from given xpath
     * @function 
     * 
     * @name PG.Util.getObjectFromXpath
     * 
     * @param {String} xpath 
     * @return {Array} object[]  
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.getObjectFromXpath( {String} xpath )
     * 
     * @since version 1.0.0
     */
    getObjectFromXpath: function (xpath)
    {
        'use strict';
        var result = document.evaluate(
                xpath,
                document.documentElement,
                null,
                XPathResult.ORDERED_NODE_ITERATOR_TYPE,
                null
            ),
            nodes = [];
        
        if (result) {
            var node = result.iterateNext();
            while(node) {
                nodes.push(node);
                node = result.iterateNext();
            }
        }
        
        return nodes;
    },
    
    /** 
     * show a red shape width a given box
     * @function 
     * 
     * @name createShape
     * 
     * @param {String} box (x, y, width, height)  
     * @return {null}
     * 
     * @this {Util}
     * 
     * @example
     * createShape( {Object} box )
     * 
     * @since version 1.0.0
     */
    createShape: function (box)
    {
        'use strict';
        var div = document.createElement("div");
        div.style.position = "absolute";
        div.style.top = box.y + "px";
        div.style.left = box.x + "px";
        div.style.width = box.width + "px";
        div.style.height = box.height + "px";
        div.style.zIndex = "999999999999999";
        div.style.background = "red";

        document.body.appendChild(div);
    },

    /** 
     * Return offset left and top 
     * @function 
     * 
     * @name getOffset
     * 
     * @param {Object} Html element
     * @return {Object} {x, y}
     * 
     * @this {Util}
     * 
     * @example
     * createShape( {Object} box )
     * 
     * @since version 1.0.0
     */
    getOffset : function ( el )
    {
        'use strict';
        var x = 0,
            y = 0;
        
        while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
            x += el.offsetLeft - el.scrollLeft;
            y += el.offsetTop - el.scrollTop;
            el = el.offsetParent;
        }
        return { x: x, y: y };
    },
    
    /** 
     * Return true if xpath is found
     * @function 
     * 
     * @name PG.Util.checkXpathc
     * 
     * @param {String} xpath 
     * @return {boolean} bool true or false  
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.checkXpath( {String} xpath )
     * 
     * @since version 1.0.0
     */
    checkXpath: function (xpath)
    {
        'use strict';
         var xpatArr = PG.Util.getObjectFromXpath(xpath);
        return (xpatArr.length > 0) ? true : false;
    },
    
    /**
     * Return the content of the given tag
     * @function
     * 
     * @name PG.Util.getValueFromObject
     * 
     * @param {Object} DOM Object
     * @return {String} value
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.getValueFromObject( Object | String | ... )
     * 
     * @since version 1.0.0
     */
    getValueFromObject: function (obj)
    {
        'use strict';
        var value = '';
        if(PG.Util.not_null(obj) && typeof obj === 'object') {
            if(obj.value !== undefined) {
                value = obj.value;
            }else if(obj.innerHTML !== undefined) {
                value = obj.innerHTML;
            }
        }
        return value.replace(/^\s+/g,'').replace(/\s+$/g,'');
    },
    
    /**
     * Create a cookie under Playground.[ name ]
     * @function
     * 
     * @name PG.Util.createCookie
     * 
     * @param {String} name
     * @param {String} value
     * @param {Number} [number] of days (optional)
     * @return {null} no return
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.createCookie( {String} name, {String} value, [ {Number} days ])
     * 
     * @since version 1.0.0
     */
    createCookie: function (name, value, days)
    {
        'use strict';
        
        // if localstorage enabled
        if(PG.Cache.localstorage) {
            localStorage.setItem(PG.Config.ns + '.' + name, value);
        // if localstorage not enable use cookies as polyfill
        }else {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toGMTString();
            }
            else var expires = "";
            document.cookie = PG.Config.ns + '.' + name + "=" + encodeURIComponent(value) + expires + "; path=/";
        }
    },
    
    s4 : function()
    {
        return Math.floor((1 + Math.random()) * 0x10000)
                 .toString(16)
                 .substring(1);
    },
        
    /**
     * Generate unique id for anonymous user
     * @function
     * 
     * @name PG.Util.GenerateUniqueId
     * 
     * @param {null}
     * @return {String} id
     * 
     * @this {User}
     * 
     * @example
     * PG.User.GenerateUniqueId()
     * 
     * @since version 1.0.0
     */
    GenerateUniqueId: function ()
    {
        'use strict';
        var uui = PG.Util.readCookie('unique-id'),
            checkSum;
        
        if(!PG.Util.not_null(uui)) {
           
            uui = window.top.location.host + PG.Util.s4() + PG.Util.s4() + '-' + PG.Util.s4()
                + '-' + PG.Util.s4() + '-' + PG.Util.s4() + '-' + PG.Util.s4()
                    + PG.Util.s4() + PG.Util.s4();
            PG.Util.createCookie('unique-id', uui);
        }
        if(PG.Util.not_null(PG.User)) {
            PG.User.uid = uui;
        }
        return uui;
    },
    
    /**
     * Get value of a cookie
     * @function
     * 
     * @name PG.Util.readCookie
     * 
     * @param {String} name
     * @return {String} | {null} value of the cookie
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.readCookie( {String} name)
     * 
     * @since version 1.0.0
     */
    readCookie: function (name)
    {
        'use strict';
        
        // if localstorage enabled
        if(PG.Cache.localstorage) {
            return (localStorage.getItem(PG.Config.ns + '.' + name) !== null) ? localStorage.getItem(PG.Config.ns + '.' + name) : null;
        // if localstorage not enable use cookies as polyfill
        }else {
            var nameEQ = PG.Config.ns + '.' + name + "=",
                ca = document.cookie.split(';'),
                i = 0,
                c = '';
                
            for(i = 0; i < ca.length; i++) {
                c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameEQ) == 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
        }
        return null;
    },
    
    /**
     * Delete a cookie
     * @function
     * 
     * @name PG.Util.eraseCookie
     * 
     * @param {String} name
     * @return {null} no return
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.readCookie( {String} name)
     * 
     * @since version 1.0.0
     */
    eraseCookie: function (name)
    {
        'use strict';
        
        // if localstorage enabled
        if(PG.Cache.localstorage) {
            return localStorage.removeItem(PG.Config.ns + '.' + name);
        // if localstorage not enable use cookies as polyfill
        }else {
            var expires = 'expires=Thu, 01 Jan 1970 00:00:01 GMT';
            document.cookie = PG.Config.ns + '.' + name + "=''; " + expires + "; path=/";
        }
    },
    
    /**
     * Tell if input URL match current URL
     * @function
     * 
     * @name PG.Util.matchUrl
     * 
     * @param {String} url
     * @return {Boolean} bool true or false
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.matchUrl( {String} url)
     * 
     * @since version 1.0.0
     */
    matchUrl: function (url)
    {
        'use strict';
        try {
            url = eval(url);
            return (url.test(window.location.href));
        }catch (e) {
            return false;
        }
    },
    
    /**
     * Tell if input URL is a good url to watch
     * We need to exclude playground URL
     * @function
     * 
     * @param {String} url
     * @return {Boolean} bool true or false
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.isUrlValid( {String} url)
     * 
     * @since version 1.0.0
     */
    isUrlValid: function (url)
    {
        'use strict';
        return (PG.Config.env[PG.Config.mode].url.indexOf(url) >= 0) ? false : true;
    },
    
    /**
     * log string if PG.Config.debug allow it
     * @function
     * 
     * @name PG.Util.log
     * 
     * @param {Object} str
     * @return {null}
     * 
     * @this {Util}
     * 
     * @example
     * PG.Util.log( {String} str)
     * 
     * @since version 1.0.0
     */
    log: function (str, obj)
    {
        'use strict';
        if(PG.Config.debug) {
            console.log(">PG DEBUG :: " + str);
            if(typeof obj !== 'undefined') {
                console.log(obj);
            }
        }
    },
   
   /**
    * get dom element from object.selector and object.name
    * @function
    * 
    * @name PG.Util.getDomElemntFromItem
    * 
    * @param {Object} item object with variable
    * @param item.selector type of the DOM element
    * @param item.name id or class of the DOM element
    * @return {Object} obj DOM object
    * 
    * @this {Util}
    * 
    * @example
    * PG.Util.getDomElemntFromItem( {String} it)
    * 
    * @since version 1.0.0
    */
   getDomElemntFromItem: function (it)
   {
        'use strict';
        var obj = null,
            o;
       
        if(it.selector === 'id') {
            obj = document.getElementById(it.name);
        }else if(it.selector === 'class') {
            obj = document.getElementsByClassName(it.name);
            if(obj !== null && obj.length > 0) {
                obj = obj[0];
            }
        }
       
        return obj;
    },
   
   /**
    * polyfill function to trigger custom event
    * @function
    * 
    * @name PG.Util.fireEvtd
    * 
    * @param {String} name of the event
    * @param {Object} dom element to trigger the event (use window)
    * @param {Object} data send to the event handler
    * @return {null}
    * 
    * @this {Util}
    * 
    * @example
    * PG.Util.fireEvt( {String} name, {Object} window, {Object} data)
    * 
    * @since version 1.0.0
    */
    fireEvt: function (type, obj, data)
    {
        'use strict';
        if(window.dispatchEvent) {
            var event = document.createEvent('Event');
            event.initEvent(type, true, true);
            event.data = data;
            obj.dispatchEvent(event);
        }else if(window.fireEvent) {
            obj.fireEvent(type);
        }
    },
    
   /**
    * return an array of 2 entry, first the browser name and the version
    * @function
    * 
    * @name PG.Util.saysWho
    * 
    * @param {null}
    * @return {String} browser[]
    * 
    * @this {Util}
    * 
    * @example
    * PG.Util.saysWho( }
    * 
    * @since version 1.0.0
    */
    saysWho: function()
    {
        'use strict';
        var N = navigator.appName, ua= navigator.userAgent, tem,
        M = ua.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);
        
        if(M && (tem= ua.match(/version\/([\.\d]+)/i))!== null) M[2]= tem[1];
        M = M? [M[1], M[2]]: [N, navigator.appVersion,'-?'];
        return M;
    },
    
   /**
    * Load js file and call the param function when it's done
    * @function
    * 
    * @name PG.Util.loadJs
    *  
    * @param {String} url
    * @param {Function} callback
    * @return {String} null
    * 
    * @this {Util}
    * 
    * @example
    * PG.Util.loadJs( {String} url }
    * 
    * @since version 1.0.0 
    */
    loadJs: function(url, callback)
    {
        'use strict';
        var s = document.createElement('script'),
            ss;
            
        s.type = 'text/javascript';
        s.async = true;
        s.src = url;
        ss = document.getElementsByTagName('script')[0];
        ss.parentNode.insertBefore(s, ss);
        
        s.addEventListener('load', function ()
        {
            if(callback) {
                callback();
            }
        });
        
        return;
    }
};

// put the util into Playground.Util
try {
    addToNamespace('Util', pgutil);
    addToNamespace('Config', pl_config);
}catch(e) {
   throw new Error( "Cannot extends 'Util' to 'Playground.Util'" );
}
;