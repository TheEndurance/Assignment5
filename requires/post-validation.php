<?php


$vendorValidationMessage = array(
    "V_VendorNo" => "Vendor number must be numeric only and 4 digits in length",
    "VendorName" => "Vendor name must contain at least one character",
    "Address1" => "Address 1 must contain at least one character",
    "Address2" => "",
    "City" => "A city must have at least one character, and no numbers",
    "Prov" => "Incorrect province format, correct format is ON, AB, KA, etc",
    "PostCode" => "Incorrect postal code, acceptable formats are N2L2S3 or 60093",
    "Country" => "Country should be at least one character, no numbers",
    "Phone" => "Incorrect phone number, acceptable format is 999-999-9999",
    "Fax" => "Incorrect fax number, acceptable format is 999-999-9999"
);

$vendorDataValidation = array(
    "V_VendorNo" => "/^[0-9]{4}$/",
    "VendorName" => "/[^\s].+/",
    "Address1" => "/[^\s].+/",
    "Address2" => "/.*/",
    "City" => "/[^\s].+/",
    "Prov" => "/[A-Z]{2}/",
    "PostCode" => "/^[a-zA-Z0-9]{6}$/",
    "Country" => "/[a-zA-Z]+/",
    "Phone" => "/^[0-9]{3}[-\s]{1}[0-9]{3}[-\s]{1}[0-9]{4}$/",
    "Fax" =>"/^[0-9]{3}[-\s]{1}[0-9]{3}[-\s]{1}[0-9]{4}$/"
);


$partValidationMessage = array(
    "P_VendorNo" => "A Vendor must be selected",
    "Description" => "Description must contain atleast one character",
    "OnHand" => "Parts on hand can only be a numeric value",
    "OnOrder" => "Parts on order can only be a numeric value",
    "Cost" => "Cost can only be a numeric value",
    "ListPrice" => "List price can only be a numeric value"
);

$partDataValidation = array(
    "P_VendorNo" => "/[0-9]+/",
    "Description" => "/[^\s].+/",
    "OnHand" => "/^\d+$/",
    "OnOrder" => "/^\d+$/",
    "Cost" => "/^\d+$/",
    "ListPrice" => "/^\d+$/"
);

$vendorQueryValidationMessage = array(
    "Q_Description" => "Description must contain atleast one letter or number",
);

$vendorQueryDataValidation = array(
    "Q_Description" => "/[a-zA-Z0-9]+/",
);
/*
* Helper function for ValidatePost, that returns true if the value is the specified data type.
*/
function ValidateRegex($value, $regex)
{
    if (preg_match($regex, $value)==1) {
        return true;
    }
    return false;
}

/*
* Validates a POST value with name $postName, and adds an error message to 
* the $errors array if it is empty and returns null.  
* Otherwise it will return the POST value.
*/
function ValidatePost($postName, $displayName, $tableDataValidation, $tableValidationMessage, $allowNull = false, $customMessage = "")
{
    global $errors;

    if (empty($_POST[$postName])) { //generic field is required message
        if ($allowNull==false) {
            $errors[$postName]= $displayName . ' is a required field';
        }
        return null;
    } elseif (!ValidateRegex($_POST[$postName], $tableDataValidation[$postName])) {//POST value does not pass the regular expression validation
        if (strlen($customMessage)>0) { // Check if a custom error message has been specified
            $errors[$postName] = $customMessage;
            return null;
        } else { // else use the generic data type error message
            $errors[$postName] = $tableValidationMessage[$postName];
            return null;
        }
    } else {
        return addslashes($_POST[$postName]); //return the post value
    }
}

/*
* Validates a POST value with name $postName, and adds an error message to 
* the $errors array if it is empty and returns null. 
* Also validates the post by checking against a regular expression
* and a custom predicate function passed as $predicate parameter 
* Otherwise it will return the POST value.
*/
function ValidatePostByPredicate($postName, $displayName, $tableDataValidation, $tableValidationMessage, $predicate, $customMessage)
{
    global $errors;

    if (empty($_POST[$postName])) { //generic field is required message
        $errors[$postName]= $displayName . ' is a required field';
        return null;
    } elseif (!ValidateRegex($_POST[$postName], $tableDataValidation[$postName])) { //POST value does not pass the regular expression validation
         $errors[$postName] = $tableValidationMessage[$postName];
            return null;
    } elseif (call_user_func($predicate,$_POST[$postName])) { //if the predicate logic is true for the POST value
        $errors[$postName] = $customMessage;
        return null;
    } else {
        return addslashes($_POST[$postName]); //return the post value
    }
}


function DuplicateVendorPredicate($postValue)
{
    global $vendorsQuery;
    while ($row = $vendorsQuery->fetch()) {
        if ($row['VendorNo']==$postValue) {
            echo ($row['VendorNo']);
            echo ($postValue);
            return true;
        }
    }
        return false;
}
