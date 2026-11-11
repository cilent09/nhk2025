'use strict';
$(document).ready(function () {

    // $('#date,#datejoin').bootstrapMaterialDatePicker({
    //        time: false,
    //        clearButton: true
    //    });
    //  $("#example-date-inputS").bootstrapMaterialDatePicker({
    //                time: false,
    //                clearButton: true
    //            });

    $("#basic-forms").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        autoFocus: true
    });
    $("#verticle-wizard").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slide",
        stepsOrientation: "vertical",
        autoFocus: true
    });

    $("#design-wizard").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        autoFocus: true
    });





});
