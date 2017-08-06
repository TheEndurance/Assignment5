<?php

//Vendor Table Data Types
$vendorDataTypes = array(
    "VendorNo" => "number",
    "VendorName" => "string",
    "Address1" => "string",
    "Address2" => "string",
    "City" => "string",
    "Prov" => "string",
    "PostCode" => "string",
    "Country" => "string",
    "Phone" => "string",
    "Fax" => "string"
);
//Parts Table Data Types
$partValidationMessage = array(
    "VendorNo" => "A Vendor must be selected",
    "Description" => "Description must contain atleast one character",
    "OnHand" => "Parts on hand can only be a numeric value",
    "OnOrder" => "Parts on order can only be a numeric value",
    "Cost" => "Cost can only be a numeric value",
    "ListPrice" => "List price can only be a numeric value"
);

$partDataValidation = array(
    "VendorNo" => "/[0-9]+/",
    "Description" => "/.+/",
    "OnHand" => "/[0-9]+/",
    "OnOrder" => "/[0-9]+/",
    "Cost" => "/[0-9]+/",
    "ListPrice" => "/[0-9]+/"
);
/*
* Helper function for ValidatePost, that returns true if the value is the specified data type.
*/
function ValidateRegex($value, $regex)
{
    if(preg_match($regex,$value)==1){
        return true;
    }
    return false;
}

/*
* Validates a POST value with name $postName, and adds an error message to 
* the $errors array if it is empty and returns null.  
* Otherwise it will return the POST value.
*/
function ValidatePost($postName, $displayName, $tableDataValidation,$tableValidationMessage,$allowNull = false, $customMessage = "")
{
    global $errors;
    
    if (empty($_POST[$postName])) { //generic field is required message
        if ($allowNull==false) {
            $errors[$postName]= $displayName . ' is a required field';
        }
        return null;
    } elseif (!ValidateRegex($_POST[$postName], $tableDataValidation[$postName])) { //POST value is not the correct data type
        if (strlen($customMessage)>0) { // Check if a custom error message has been specified
            $errors[$postName] = $customMessage;
            return null;
        } else { // else use the generic data type error message
            $errors[$postName] = $tableValidationMessage[$postName];
            return null;
        }
    } else {
        return addslashes($_POST[$postName]); //return the popst value
    }
}

/*
* Sets the value of an input field in a form if the page has been posted and the value is set
*/
function StickyForm($postVariable){
    if(isset($_POST[$postVariable])){
        return $_POST[$postVariable];
    } else {
        return null;
    }
}