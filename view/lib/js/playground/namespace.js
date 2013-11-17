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
 * create a namespace and put the destination variable
 * into the namespace source
 * 
 * @param {Object} destination
 * @param {Object} source
 * 
 * @return {Object} destination
 * 
 * @ignore
 */
function extend (destination, source)
{
    'use strict';
    
    var toString = Object.prototype.toString,  
        objTest = toString.call({}),
        property;
    
    for (property in source) {
        if (source[property] && objTest == toString.call(source[property])) {  
            destination[property] = destination[property] || {};  
            extend(destination[property], source[property]);  
        } else {  
            destination[property] = source[property];  
        }  
    }
    
    return destination;  
}

/**
 * Add a variable to the namespace "Playground"
 * then call extends() to create the namespace
 * 
 * @param {Object} ns
 * @param {Object} obj
 * @return {Object} destination
 * 
 * @ignore
 */
function addToNamespace (ns, obj)
{
    'use strict';
    
    var o = {};
    o[ns] = obj;
    
    return extend (
        window.Playground,
        o
    );  
}
;