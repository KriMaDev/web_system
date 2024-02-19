<?php
// ======================================================
//                                 Functions Library File
// ======================================================
function greekToEnglishChars($filestr)
    {
        $filestr = trim($filestr);
        // Replace Greek Letters
        $greek   = array('α','ά','Ά','Α','β','Β','γ', 'Γ', 'δ','Δ','ε','έ','Ε','Έ','ζ','Ζ','η','ή','Η','θ','Θ','ι','ί','ϊ','ΐ','Ι','Ί', 'κ','Κ','λ','Λ','μ','Μ','ν','Ν','ξ','Ξ','ο','ό','Ο','Ό','π','Π','ρ','Ρ','σ','ς', 'Σ','τ','Τ','υ','ύ','Υ','Ύ','φ','Φ','χ','Χ','ψ','Ψ','ω','ώ','Ω','Ώ',"'","'",','); 
        $english = array('a', 'a','A','A','b','B','g','G','d','D','e','e','E','E','z','Z','i','i','I','th','Th', 'i','i','i','i','I','I','k','K','l','L','m','M','n','N','x','X','o','o','O','O','p','P' ,'r','R','s','s','S','t','T','y','y','Y','Y','f','F','ch','Ch','ps','Ps','o','o','O','O','_','_','_'); 
        $filestr = str_replace($greek, $english, $filestr);
        
        // Change or Remove Special Chars
        $filestr = str_replace(" - ", "-", $filestr);  // Change chars to '-'
        $filestr = str_replace(" -",  "-", $filestr);  // Change chars to '-'
        $filestr = str_replace("- ",  "-", $filestr);  // Change chars to '-'
        $filestr = str_replace(" ",   "-", $filestr);  // Change space char to '-'
        $filestr = str_replace("\\",  "-", $filestr);  // Change '\' char to '-'
        $filestr = str_replace("/",   "-", $filestr);  // Change '\' char to '-'
        $filestr = str_replace(".",   "",  $filestr);  // Remove '.' char
        $filestr = str_replace(",",   "",  $filestr);  // Remove ',' char
        $filestr = str_replace("'",   "",  $filestr);  // Remove ' char
        $filestr = str_replace("!",   "",  $filestr);  // Remove '!' char
        $filestr = str_replace("~",   "",  $filestr);  // Remove '~' char
        $filestr = str_replace("`",   "",  $filestr);  // Remove '`' char
        $filestr = str_replace("!",   "",  $filestr);  // Remove '!' char
        $filestr = str_replace("@",   "",  $filestr);  // Remove '@' char
        $filestr = str_replace("#",   "",  $filestr);  // Remove '#' char
        $filestr = str_replace("$",   "",  $filestr);  // Remove '$' char
        $filestr = str_replace("%",   "",  $filestr);  // Remove '%' char
        $filestr = str_replace("^",   "",  $filestr);  // Remove '^' char
        $filestr = str_replace("&",   "",  $filestr);  // Remove '&' char
        $filestr = str_replace("*",   "",  $filestr);  // Remove '*' char
        $filestr = str_replace("(",   "",  $filestr);  // Remove '(' char
        $filestr = str_replace(")",   "",  $filestr);  // Remove ')' char
        $filestr = str_replace("+",   "",  $filestr);  // Remove '+' char
        $filestr = str_replace(":",   "",  $filestr);  // Remove ':' char
        $filestr = str_replace("[",   "",  $filestr);  // Remove '[' char
        $filestr = str_replace("]",   "",  $filestr);  // Remove ']' char
        $filestr = str_replace("{",   "",  $filestr);  // Remove '{' char
        $filestr = str_replace("}",   "",  $filestr);  // Remove '}' char
        $filestr = str_replace(";",   "",  $filestr);  // Remove ';' char
        $filestr = str_replace("\"",  "",  $filestr);  // Remove '"' char
        $filestr = str_replace("|",   "",  $filestr);  // Remove '|' char
        $filestr = str_replace("<",   "",  $filestr);  // Remove '<' char
        $filestr = str_replace(">",   "",  $filestr);  // Remove '>' char
        $filestr = str_replace("?",   "",  $filestr);  // Remove '?' char
        $filestr = str_replace("=",   "",  $filestr);  // Remove '=' char
        $filestr = str_replace("_",   "",  $filestr);  // Remove '_' char
        $filestr = str_replace("--",  "-", $filestr);  // Replace '--' char
        $filestr = str_replace("---", "-", $filestr);  // Replace '---' char
        $filestr = str_replace("«", "", $filestr);     // Remove '«' char
        $filestr = str_replace("»", "", $filestr);     // Remove '»' char
        $filestr = str_replace("”", "", $filestr);     // Remove '”' char
        $filestr = str_replace("“", "", $filestr);     // Remove '“' char
        $filestr = str_replace("΄", "", $filestr);     // Remove '΄' char
        
        return strtolower($filestr);			
    }  // End of Function
?>