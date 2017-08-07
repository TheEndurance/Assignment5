var vendorNo = document.getElementById("VendorNo");
var description = document.getElementById("Description");
var onHand = document.getElementById("OnHand");
var onOrder = document.getElementById("OnOrder");
var cost = document.getElementById("Cost");
var listPrice = document.getElementById("ListPrice");

var ErrorMessages = function () {
    var compoundErrorMessages = {
        PartsForm: [],
        VendorsForm: []
    };

    var PartsValidationMessage = {
        "VendorNo": "A Vendor must be selected",
        "Description": "Description must contain atleast one character",
        "OnHand": "Parts on hand can only be a numeric value",
        "OnOrder": "Parts on order can only be a numeric value",
        "Cost": "Cost can only be a numeric value",
        "ListPrice": "List price can only be a numeric value"
    }
    var PartsDataValidation = {
        "VendorNo": /[0-9]+/,
        "Description": /.+/,
        "OnHand": /[0-9]+/,
        "OnOrder": /[0-9]+/,
        "Cost": /[0-9]+/,
        "ListPrice": /[0-9]+/
    }

    var AddErrorMessage = function (id, message) {
        'use strict';
        var newId = id + "Error";
        //check for existence of the span
        var span = document.getElementById(newId);
        if (span) {
            span.firstChild.value = message;
        } else {
            console.log(id);
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
        PartsValidationMessage: PartsValidationMessage,
        PartsDataValidation: PartsDataValidation,
        compoundErrorMessages: compoundErrorMessages
    }
}();

var FormController = function (errorMessages) {

    var ValidateSubmission = function (e) {
        "use strict";
        var id = e.target.id;
        if (id == "PartsForm") {
            var validationMessage = errorMessages.PartsValidationMessage;
            var validationRules = errorMessages.PartsDataValidation;
        } else if (id == "VendorsForm") {
            //TODO:
        }
        try {
            for (var key in validationMessage) {
                if (document.forms[id][key].value == null || !validationRules[key].test(document.forms[id][key].value)) {
                    errorMessages.AddErrorMessage(key, validationMessage[key]);
                    errorMessages.AddCompoundErrorMessage(validationMessage[key], id);
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
        var validationMessage;
        var validationRules;
        if (formID == "PartsForm") {
            validationMessage = errorMessages.PartsValidationMessage;
            validationRules = errorMessages.PartsDataValidation;
        } else if (formID == "VendorsForm") {
            //TODO:
        }
        if (!validationRules[key].test(document.forms[formID][key].value)) {
            errorMessages.AddErrorMessage(key, validationMessage[key]);
            errorMessages.AddCompoundErrorMessage(validationMessage[key], formID);
        } else {
            errorMessages.RemoveErrorMessage(key);
            errorMessages.RemoveCompoundErrorMessage(validationMessage[key], formID);
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
    partsForm.addEventListener('submit', FormController.ValidateSubmission, false);

    vendorNo.addEventListener('blur', FormController.ValidateField, false);
    description.addEventListener('blur', FormController.ValidateField, false);
    onOrder.addEventListener('blur', FormController.ValidateField, false);
    onHand.addEventListener('blur', FormController.ValidateField, false);
    cost.addEventListener('blur', FormController.ValidateField, false);
    listPrice.addEventListener('blur', FormController.ValidateField, false);


    

}