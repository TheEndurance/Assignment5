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
$partDataTypes = array(
    "VendorNo" => "number",
    "Description" => "string",
    "OnHand" => "number",
    "OnOrder" => "number",
    "Cost" => "number",
    "ListPrice" => "number"
);

/*
* Helper function for ValidatePost, that returns true if the value is the specified data type.
*/
function ValidateDataType($value, $dataType)
{
    switch ($dataType) {
        case "number":
            if (is_numeric($value)) {
                return true;
            }
            break;
        case "string":
            if (is_string($value)) {
                return true;
            }
            break;
        default:
            return false;
    }
}

/*
* Validates a POST value with name $postName, and adds an error message to 
* the $errors array if it is empty and returns null.  
* Otherwise it will return the POST value.
*/
function ValidatePost($postName, $displayName, $tableDataTypes,$allowNull = false, $customMessage = "")
{
    global $errors;
    
    if (empty($_POST[$postName])) { //generic field is required message
        if ($allowNull==false) {
            $errors[$postName]= $displayName . ' is a required field.';
        }
        return null;
    } elseif (!ValidateDataType($_POST[$postName], $tableDataTypes[$postName])) { //POST value is not the correct data type
        if (strlen($customMessage)>0) { // Check if a custom error message has been specified
            $errors[$postName] = $customMessage;
            return null;
        } else { // else use the generic data type error message
            $errors[$postName] = $displayName . ' data type must be a ' . $tableDataTypes[$postName] . '.';
            return null;
        }
    } else {
        return addslashes($_POST[$postName]); //return the popst value
    }
}
