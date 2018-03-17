document.addEventListener('deviceready', onDeviceReady, false);

/* Global variables to make records available to views */
var medications;
var doses;

var dbShell;

var storage = window.localStorage;

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

function onDeviceReady() {

    $('.app').find('.sync-button').click(function () {
        console.log('Should be syncing now!');
        $.ajax({
            url: 'http://www.andrewedwards.co.uk/asthma/rest/medications',
            dataType: "json",
            method: "GET",
            success: function (data, textStatus, jqXHR) {
                console.log(JSON.stringify(data));
                $('.app').find('.sync-button').append('<span class="entypo-thumbs-up"></span>');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
                $('.app').find('.sync-button').after('<p>' + errorThrown + '</p>');
            }
        });
    });

    if ( $('.app').find('.sync-with-cloud').length ) {

    }

    if ( $('.app').find('.medication-tables').length ) {
        loadMedications();
        renderMedicationList( $('.medication-tables').first().data('mode') );
    }

    if ( $('.app').find('.load-medication-get-id').length ) {
        var id = new URLSearchParams(location.search).get('id');
        loadMedications();
        var medication = medications[id];
        $('.load-medication-get-id').first().attr('id', id);

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

        if ( form.attr('id') ) {
            saveMedication( medication, form.attr('id') );
        } else {
            saveMedication( medication );
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
function saveMedication(medication, id = -1) {
    console.log(id);
    if ( !medications ) {
        loadMedications();
    }

    medication.updated_at = Date.now();

    if (id >= 0) {
        medications[id] = medication;
    } else {
        medication.created_at = Date.now();
        medications.push( medication );
    }
    storage.setItem( 'medications', JSON.stringify(medications) );
}
function loadMedications() {
    console.log('Loading medications...');
    medications = JSON.parse( storage.getItem('medications') );
    if ( !medications ) {
        console.log('...');
        medications = [];
        storage.setItem( 'medications', JSON.stringify([]) );
        /*
        saveMedication({
            'name': 'Sirdupla',
            'amount': 30,
            'unit': 'mg',
            'type': 'preventer',
            'reminders': ["09:00", "21:00"],
    	    'updated_at': Date.now(),
    	    'created_at': Date.now(),
        });
        */
    }
    console.log('Success!');
}

/*
Save/load doses to/from localStorage, using the 'doses' variable in the form:
    [{
        medication_id,
        datetime
    }]
*/
// This needs improving, so that the param is checked for validity
function saveDose(dose) {
    if ( !doses ) {
        loadDose();
    }
    doses.push( dose );
    storage.setItem( 'doses', JSON.stringify(doses) );
}
function loadDose() {
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
                        '<th><a href="#" class="btn btn-primary add-dose-button" data-medication-id="' + i + '"><span class="entypo-plus"></span></a></th>'
                ) +
            '</tr>'
        );
    }
    // Bind function to add dose buttons
    $('.add-dose-button').click(function() {
        saveDose({
            'medication_id': $(this).data('medication-id'),
            'taken_at': Date.now(),
        });
    });
}

function capitalise(s) {
    return s && s[0].toUpperCase() + s.slice(1);
}
