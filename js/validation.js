var ErrorMessages = function () {
    var compoundErrorMessages = {
        PartsForm: [],
        VendorsForm: [],
        VendorQueryForm: []

    };
    var validationMessages = {
        PartsForm: {
            "P_VendorNo": "A Vendor must be selected",
            "Description": "Description must contain at least one character",
            "OnHand": "Parts on hand can only be a numeric value",
            "OnOrder": "Parts on order can only be a numeric value",
            "Cost": "Cost can only be a numeric value",
            "ListPrice": "List price can only be a numeric value"
        },
        VendorsForm: {
            "V_VendorNo": "Vendor number must be numeric only and 4 digits in length",
            "VendorName": "Vendor name must contain at least one character",
            "Address1": "Address 1 must contain at least one character",
            "Address2": "",
            "City": "A city must have at least one character, and no numbers",
            "Prov": "Incorrect province format, correct format is ON, AB, KA, etc",
            "PostCode": "Incorrect postal code, acceptable formats are N2L2S3 or 60093",
            "Country": "Country should be at least one character, no numbers",
            "Phone": "Incorrect phone number, acceptable format is 999-999-9999",
            "Fax": "Incorrect fax number, acceptable format is 999-999-9999"
        },
        VendorQueryForm: {
            "Q_Description": "Part description must contain at least one character or number"
        }
    };
    var dataValidationRules = {
        PartsForm: {
            "P_VendorNo": /[0-9]+/,
            "Description": /[^\s//].+/,
            "OnHand": /^\d+$/,
            "OnOrder": /^\d+$/,
            "Cost": /^\d+$/,
            "ListPrice": /^\d+$/
        },
        VendorsForm: {
            "V_VendorNo": /^[0-9]{4}$/,
            "VendorName": /[^\s//].+/,
            "Address1": /[^\s//].+/,
            "Address2": /.*/,
            "City":  /[^\s//].+/,
            "Prov": /[A-Z]{2}/,
            "PostCode": /^[a-zA-Z0-9]{6}$/,
            "Country": /[^\s//].+/,
            "Phone": /^[0-9]{3}[-\s]{1}[0-9]{3}[-\s]{1}[0-9]{4}$/,
            "Fax": /^[0-9]{3}[-\s]{1}[0-9]{3}[-\s]{1}[0-9]{4}$/
        },
        VendorQueryForm: {
            "Q_Description": /[a-zA-Z0-9]+/
        }

    };
    var AddErrorMessage = function (id, message) {
        'use strict';
        var newId = id + "Error";
        //check for existence of the span
        var span = document.getElementById(newId);
        if (span) {
            span.firstChild.value = message;
        } else {
            $("#" + id).parent('div').append('<span id="' + newId + '" class="text-danger">' + message + '</span>');
            $("#" + id).focus();
        }
    }
    var RemoveErrorMessage = function (id) {
        'use strict';
        $("#" + id + "Error").remove();
    }
    var AddCompoundErrorMessage = function (message, formID) {
        "use strict";
        console.log(formID);
        for (var i = 0; i < compoundErrorMessages[formID].length; i++) {
            if (compoundErrorMessages[formID][i] == message) {
                return;
            }
        }
        compoundErrorMessages[formID].push(message);
    }
    var RemoveCompoundErrorMessage = function (message, formID) {
        "use strict";
        for (var i = 0; i < compoundErrorMessages[formID].length; i++) {
            if (compoundErrorMessages[formID][i] == message) {
                compoundErrorMessages[formID].splice(i, 1);
                break;
            }
        }
    }
    var UpdateCompoundErrors = function (formID) {
        "use strict";
        var compoundErrorLists = $("#" + formID + "Errors");
        var tempCompoundList = "";
        if (compoundErrorMessages[formID].length > 0) {
            $(compoundErrorLists).html('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.</div>');
            for (var i = 0; i < compoundErrorMessages[formID].length; i++) {
                tempCompoundList += '<li class="text-danger">' + compoundErrorMessages[formID][i] + '</li>';
            }
            $(compoundErrorLists).append(tempCompoundList);
        } else {
            $(compoundErrorLists).html("");
        }
    }
    return {
        AddErrorMessage: AddErrorMessage,
        RemoveErrorMessage: RemoveErrorMessage,
        AddCompoundErrorMessage: AddCompoundErrorMessage,
        RemoveCompoundErrorMessage: RemoveCompoundErrorMessage,
        UpdateCompoundErrors: UpdateCompoundErrors,
        validationMessages: validationMessages,
        dataValidationRules: dataValidationRules,
        compoundErrorMessages: compoundErrorMessages
    }
}();

var FormController = function (errorMessages) {
    var ValidateSubmission = function (e) {
        "use strict";
        var id = e.target.id;
        try {
            for (var key in errorMessages.validationMessages[id]) {
                if (document.forms[id][key].value == null || !errorMessages.dataValidationRules[id][key].test(document.forms[id][key].value)) {
                    errorMessages.AddErrorMessage(key, errorMessages.validationMessages[id][key]);
                    errorMessages.AddCompoundErrorMessage(errorMessages.validationMessages[id][key], id);
                    console.log(id);
                    errorMessages.UpdateCompoundErrors(id);
                    $("#" + key).focus();
                }
            }
        } catch (exception) {
            console.log(exception);
        }
        if (errorMessages.compoundErrorMessages[id].length > 0) {
            e.preventDefault();
        }
    }
    var ValidateField = function (e) {
        "use strict"
        var key = e.target.id;
        var formID = $("#" + key).parents("form").first().attr('id');
        if (!errorMessages.dataValidationRules[formID][key].test(document.forms[formID][key].value)) {
            errorMessages.AddErrorMessage(key, errorMessages.validationMessages[formID][key]);
            errorMessages.AddCompoundErrorMessage(errorMessages.validationMessages[formID][key], formID);
        } else {
            errorMessages.RemoveErrorMessage(key);
            errorMessages.RemoveCompoundErrorMessage(errorMessages.validationMessages[formID][key], formID);
        }
        errorMessages.UpdateCompoundErrors(formID);
    }

    return {
        ValidateSubmission: ValidateSubmission,
        ValidateField: ValidateField
    }
}(ErrorMessages);

window.onload = function () {
    var partsForm = document.getElementById("PartsForm");
    var vendorsForm = document.getElementById("VendorsForm");
    var vendorQueryForm = document.getElementById("VendorQueryForm");

    partsForm.addEventListener('submit', FormController.ValidateSubmission, false);
    vendorsForm.addEventListener('submit', FormController.ValidateSubmission, false);
    vendorQueryForm.addEventListener('submit',FormController.ValidateSubmission,false);

    for (var j = 0; j < document.forms.length; j++) {
        for (var i = 0; i < document.forms[j].length-1 ; i++) {
            document.forms[j][i].addEventListener('blur', FormController.ValidateField, false);
        }
    }

}