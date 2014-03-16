
// this function is taken from here: http://phpjs.org/functions/stristr:538
function stristr (haystack, needle, bool) {
    // Finds first occurrence of a string within another, case insensitive  
    // 
    // version: 909.322
    // discuss at: http://phpjs.org/functions/stristr
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfxied by: Onno Marsman
    // *     example 1: stristr('Kevin van Zonneveld', 'Van');
    // *     returns 1: 'van Zonneveld'
    // *     example 2: stristr('Kevin van Zonneveld', 'VAN', true);
    // *     returns 2: 'Kevin '
    var pos = 0;

    haystack += '';
    pos = haystack.toLowerCase().indexOf( (needle+'').toLowerCase() );
    if (pos == -1){
        return false;
    } else{
        if (bool) {
            return haystack.substr( 0, pos );
        } else{
            return haystack.slice( pos );
        }
    }
}




// 2010-01-29 - this is from here: 
// http://phpjs.org/functions/substr:558
function substr (str, start, len) {
    // Returns part of a string  
    // 
    // version: 909.322
    // discuss at: http://phpjs.org/functions/substr
    // +     original by: Martijn Wieringa
    // +     bugfixed by: T.Wild
    // +      tweaked by: Onno Marsman
    // +      revised by: Theriault
    // *       example 1: substr('abcdef', 0, -1);
    // *       returns 1: 'abcde'
    // *       example 2: substr(2, 0, -6);
    // *       returns 2: false
// Add: (?) Use unicode.semantics and/or unicode.runtime_encoding (e.g., with string wrapped in "binary" or "Binary" class) to
// allow access of binary (see file_get_contents()) by: charCodeAt(x) & 0xFF (see https://developer.mozilla.org/En/Using_XMLHttpRequest )
// Fix: Handle 4-byte characters
 
    str += '';
    var end = str.length;
    if (start < 0) {
        start += end;
    }
    end = typeof len === 'undefined' ? end : (len < 0 ? len + end : len + start);
    // PHP returns false if start does not fall within the string.
    // PHP returns false if the calculated end comes before the calculated start.
    // PHP returns an empty string if start and end are the same.
    // Otherwise, PHP returns the portion of the string from start to end.
    return start >= str.length || start < 0 || start > end ? !1 : str.slice(start, end);
}







// from here:
// http://phpjs.org/functions/substr:558
function array_unique (inputArr) {
    // Removes duplicate values from array  
    // 
    // version: 912.1315
    // discuss at: http://phpjs.org/functions/array_unique    // +   original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
    // +      input by: duncan
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Nate
    // +      input by: Brett Zamir (http://brett-zamir.me)    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Michael Grier
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
   // %          note 1: the second argument, sort_flags is not implemented
    // -    depends on: asort
    // *     example 1: array_unique(['Kevin','Kevin','van','Zonneveld','Kevin']);
    // *     returns 1: {0: 'Kevin', 2: 'van', 3: 'Zonneveld'}
    // *     example 2: array_unique({'a': 'green', 0: 'red', 'b': 'green', 1: 'blue', 2: 'red'});
    // *     returns 2: {a: 'green', 0: 'red', 1: 'blue'}
    var key = '', tmp_arr2 = [], val = ''; 
    var __array_search = function (needle, haystack) {
        var fkey = '';
        for (fkey in haystack) {
            if (haystack.hasOwnProperty) {                
                if ((haystack[fkey] + '') === (needle + '')) {
                    return fkey;
                }
            }
        }
        return false;
    };
 
    for (key in inputArr) {
        if (inputArr.hasOwnProperty) {
            val = inputArr[key];
            if (false === __array_search(val, tmp_arr2)) {
                tmp_arr2[key] = val;
            }
        }
    }
 
    return tmp_arr2;
}




