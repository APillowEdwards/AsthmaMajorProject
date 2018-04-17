/* Global variables to make records available to views */
var medications;
var doses;

var dbShell;

var storage = window.localStorage;

var base_url = 'http://192.168.0.17/Asthma/rest/web/';

/* Fix to remove proxy in Ajax calls, by 'mesompi' on StackOverflow */
(function() {
    var xhr = {};
    xhr.open = XMLHttpRequest.prototype.open;

    XMLHttpRequest.prototype.open = function (method, url) {
        console.log(url);
        if(url.indexOf('/proxy/') == 0){
            url = window.decodeURIComponent(url.substr(7));
        }
        xhr.open.apply(this, arguments);
    }
})(window);

document.addEventListener('deviceready', onDeviceReady, false);

function onDeviceReady() {

    if ( $('.app').hasClass('redirect-if-logged-in') ) {
        if ( localStorage.getItem("credentials") ) {
            $(location).attr('href', "dashboard.html");
        }
    }

    $('.app').find('.login-button').click( function() {
        var username = $('#inputUsername').val();
        var password = $('#inputPassword').val();
        var credentials = btoa(username + ":" + password);
        $.ajax({
            url: base_url + 'medication',
            dataType: "json",
            method: "GET",
            headers: {
                "Authorization": "Basic " + credentials
            },
            success: function (data, textStatus, jqXHR) {
                localStorage.setItem("credentials", credentials);
                $(location).attr('href', "dashboard.html");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('.app').find('.login-button').append('<span class="entypo-thumbs-down"></span>');
                $('.app').find('.login-button').after('<p class="sync-error">' + errorThrown + ':' + textStatus + ':' + base_url + 'medication' + '</p>');
            }
        });
    });

    $('nav').find('.log-out-button').click( function() {
        console.log("Removed credentials");
        localStorage.setItem("credentials", "");
    });

    if ( $('.app').find('.sync-button').length ) {
      $.getScript('js/mylibs/sync.js');
    }
    if ( $('.app').find('.medication-tables').length ) {
        renderMedicationList( $('.medication-tables').first().data('mode') );
    }

    if ( $('.app').find('.load-medication-form-attributes').length ) {
        var id = new URLSearchParams(location.search).get('id');
        if ( !medications ) {
            loadMedications();
        }
        var medication = medications[id];
        $('.load-medication-form-attributes').first().attr('id', id);

        $('.loaded-medication-name').html(medication.name);
        $('#inputName').val(medication.name);
        $('.loaded-medication-amount').html(medication.amount);
        $('#inputAmount').val(medication.amount);
        $('.loaded-medication-unit').html(medication.unit);
        $('#inputUnit').val(medication.unit);
        $('.loaded-medication-type').html(medication.type);
        $('#inputType' + capitalise(medication.type) + ':radio').click();
        if ( medication.type != 'reliever' ) {
            $('#inputReminderFrequency' + medication.reminders.length + ':radio').click();
            for (var i = 0, reminders = medication.reminders.length; i < reminders; i++) {
                $('#inputReminderAt' + (i + 1)).val(medication.reminders[i]);
            }
        }
    }

    $('.save-medication-button').click(function() {
        if ( !$(this).attr('disabled') ) {
            var form = $(this).parents('form');
            var medication = {
                'name': form.find('#inputName').first().val(),
                'amount': form.find('#inputAmount').first().val(),
                'unit': form.find('#inputUnit').first().val(),
                'type': form.find('#inputType').find(':checked').val(),
            }

            if ( medication.type != 'reliever' ) {
                medication.reminders = [];
                for (var i = 0, reminders = form.find('#inputReminderFrequency').find(':checked').val(); i < reminders; i++) {
                    medication.reminders.push( form.find('#inputReminderAt' + (i + 1)).val() );
                }
            }

            var success = false;
            if ( form.attr('id') ) {
                if ( saveMedication( medication, form.attr('id') ) ) {
                    window.plugins.toast.showLongCenter('Saved ' + medication.name);
                    success = true;
                } else {
                    window.plugins.toast.showLongCenter('Saving ' + medication.name + ' Failed');
                }
            } else {
                if ( saveMedication( medication ) ) {
                    window.plugins.toast.showLongCenter('Added ' + medication.name);
                    success = true;
                } else {
                    window.plugins.toast.showLongCenter('Adding ' + medications.name + ' Failed');
                }
            }

            if ( success ) {
                // Expects the user to be at 'medication/*', if they aren't then this will break
                // Disable save button
                $(this).attr("disabled", true);
                $(this).addClass("disabled");
                // Adda delay to make the transistion less jarring
                window.setTimeout(function() {
                    window.location.href="index.html"
                }, 2000);
            }
        }
    });

    if ( $('.app').find('.load-medication-dose-form').length ) {
        var medication_id = new URLSearchParams(location.search).get('medication-id');
        if ( !medications ) {
            loadMedications();
        }
        $('.load-medication-form-attributes').first().attr('id', medication_id);

        var medication = medications[medication_id];
        $('.medication-name').html(medication.name);
        $('.medication-dose-size').html("" + medication.amount + medication.unit);
    }

    $('.save-dose-button').click(function() {
        if ( !$(this).attr('disabled') ) {
            var form = $(this).parents('form');
            var dose = {
                'medication_id': form.attr('id'),
                'dose_size': form.find('#inputAmount').first().val(),
                'taken_at': Date.now(),
                'new': true,
            }

            if ( saveDose(dose) ) {
                window.plugins.toast.showLongCenter('Saved ' + medication.name);
                $(this).addClass("disabled");
                $(this).attr("disabled", true);
                // Add a delay to make the transistion less jarring
                window.setTimeout(function() {
                    window.location.href="index.html"
                }, 2000);
            } else {
                window.plugins.toast.showLongCenter('Saving ' + medication.name + ' Failed');
            }
        }
    });
}

/*
Save/load medications to/from localStorage, using the 'medication' variable in the form:
    [{
        name,
        amount,
        unit,
        type,
        reminders: [time], // Time is in the form "HH:MM"
    }]
*/
// This needs improving, so that the param is checked for validity
function saveMedication(medication, id = -1, update_updated_at = true) {
    console.log(id);
    if ( !medications ) {
        loadMedications();
    }

    if ( update_updated_at ) {
        medication.updated_at = Date.now();
    }

    if (id >= 0) {
        medication.db_id = medications[id].db_id;
        medications[id] = medication;
    } else {
        if ( typeof medication.db_id === 'undefined' ) {
            medication.db_id = -1;
        }
        medication.created_at = Date.now();
        medications.push( medication );
    }
    try {
        storage.setItem( 'medications', JSON.stringify(medications) );
    }
    catch(error) {
        console.error(error);
        return false;
    }
    return true;
}
/*
 * Load Medications from localStorage
 */
function loadMedications() {
    medications = JSON.parse( storage.getItem('medications') );
    if ( !medications ) {
        medications = [];
        storage.setItem( 'medications', JSON.stringify([]) );
    }
}

/*
Save/load doses to/from localStorage, using the 'doses' variable in the form:
    [{
        medication_id, // NOT the db-id, the actual index in the localStorage array
        datetime
    }]
*/
// This needs improving, so that the param is checked for validity
function saveDose(dose, id = -1) {
    if ( !doses ) {
        loadDoses();
    }

    if ( id >= 0 ) {
        doses[id] = dose;
    } else {
        dose.new = true;
        doses.push( dose );
    }

    try {
        storage.setItem( 'doses', JSON.stringify(doses) );
    }
    catch(error) {
        console.error(error);
        return false;
    }
    return true;
}
function loadDoses() {
    console.log('Loading doses...');
    doses = JSON.parse( storage.getItem('doses') );
    if ( !doses ) {
        console.log('Creating blank array...');
        doses = [];
        // Store the array
        storage.setItem( 'doses', JSON.stringify(doses) );
    }
    console.log('Success!');
}

/*
    @param mode
        Can be either 'editMedication' or 'addDose', for the two different lists
        Defaults to 'editMedication'
*/
function renderMedicationList(mode = 'editMedication') {
    var valid_modes = ['editMedication', 'addDose'];
    if ( !valid_modes.includes( mode ) ) {
        console.log('Invalid mode for renderMedicationList: ' + mode);
    }
    if( !medications ) {
        loadMedications();
    }
    if ( medications.length == 0 ) {
        $('.medication-tables').append(
            '<p>You don&rsquo;t have any medications set up, would you like to add one' + (mode == 'editMedication' ? ' (above)' : '') + ' or retrieve (sync) them from the website?</p>' +
            (mode == 'addDose' ? '<a href="../medication/add.html" class="btn btn-primary">Add Medication <span class="entypo-plus"></span></a><br /><br />' : '') +
            '<a href="../dashboard.html" class="btn btn-primary">Sync from Dashboard</a>'
        );
    } else {
        $('.medication-tables').append(
            '<h2 class="reliever-medication-heading d-none">Reliever Medication</h2>',
            '<table class="table btn-table medication-table reliever-medication-table d-none"></table>',
            '<h2 class="preventer-medication-heading d-none">Preventer Medication</h2>',
            '<table class="table btn-table medication-table preventer-medication-table d-none"></table>',
            '<h2 class="other-medication-heading d-none">Other Medication</h2>' ,
            '<table class="table btn-table medication-table other-medication-table d-none"></table>',
        );
        $('.medication-table').append(
            '<thead>' +
                '<tr>' +
                    '<th scope="col">Name</th>' +
                    '<th scope="col">Dose Size</th>' +
                    '<th scope="col">' + (mode == 'editMedication' ? 'Edit' : 'Add Dose') + '</th>' +
                    (mode == 'addDose' ? '<th scope="col">Custom Dose</th>' : '') +
                '</tr>' +
            '</thead>',
            '<tbody></tbody>'
        );


        for (var i = 0, len = medications.length; i < len; i++) {
            // Show the heading and this medication's table
            $('.' + medications[i].type + '-medication-heading').removeClass('d-none');
            $('.' + medications[i].type + '-medication-table').removeClass('d-none');

            // Add row to relevant table
            $( '.' + medications[i].type + '-medication-table tbody').append(
                '<tr>' +
                    '<th scope="row">' + medications[i].name + '</th>' +
                    '<th scope="row">' + medications[i].amount + medications[i].unit + '</th>' + (
                        mode == 'editMedication' ?
                            '<th><a href="edit.html?id=' + i + '" class="btn btn-primary">Edit <span class="entypo-pencil"></span></th>' :
                            '<th><a href="#" class="btn btn-primary add-dose-button" data-medication-id="' + i + '"><span class="entypo-plus"></span></a></th>' +
                            '<th><a href="add.html?medication-id=' + i + '" class="btn btn-primary"><span class="entypo-newspaper"></span></th>'
                    ) +
                '</tr>'
            );
        }
        // Bind function to add dose buttons
        $('.add-dose-button').click(function() {
            if ( !$(this).attr('disabled') ) {
                if ( saveDose({
                    'medication_id': $(this).data('medication-id'),
                    'dose_size': 1,
                    'taken_at': Date.now(),
                }) ) {
                    window.plugins.toast.showLongCenter('Added Dose of ' + medications[$(this).data('medication-id')].name);
                } else {
                    window.plugins.toast.showLongCenter('Adding Dose of ' + medications[$(this).data('medication-id')].name + ' Failed');
                };

                // Disable save button for a second
                $(this).attr("disabled", true);
                $(this).addClass("disabled");
                // Add a delay to make the transistion less jarring
                var that = $(this);
                window.setTimeout(function() {
                    that.attr("disabled", false);
                    that.removeClass("disabled");
                }, 1000);
            }
        });
    }
}

function capitalise(s) {
    return s && s[0].toUpperCase() + s.slice(1);
}
