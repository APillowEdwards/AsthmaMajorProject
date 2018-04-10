document.addEventListener('deviceready', onDeviceReady, false);

/* Global variables to make records available to views */
var medication = [];
var medicationReminders = []; // Only stores the reminders for the last requested medication id

/* Make DB Connection available to all function */
/* NEED TO PUT THIS IN SCOPE THOUGH, shouldn't be accessible from elsewhere d*/
var dbShell;

/* DB functions adapted from https://www.raymondcamden.com/2011/10/20/example-of-phonegaps-database-support/ */

function onDeviceReady() {
    // Open DB
    dbShell = window.openDatabase("Asthma", 1, "Asthma", 1000000);
    // Create initial tables
    dbShell.transaction(setupTables, errorHandler, getMedication);
}

function setupTables(tx){
    tx.executeSql(
        "CREATE TABLE IF NOT EXISTS medication(" +
        "id INTEGER PRIMARY KEY," +
        "name," +
        "type," +
        "amount DECIMAL," +
        "unit)"
    );
    tx.executeSql(
        "CREATE TABLE IF NOT EXISTS medication_reminder(" +
        "id INTEGER PRIMARY KEY," +
        "medication_id INTEGER," +
        "time REAL)" // Interpret this as a DateTime, and only use the time
    );
    tx.executeSql(
        "CREATE TABLE IF NOT EXISTS doses(" +
        "id INTEGER PRIMARY KEY," +
        "medication_id INTEGER," +
        "dose_size," +
        "taken_at REAL)" // Interpret this as a DateTime
    );
}

function errorHandler(err){
    alert("Database Error: " + err.message + "\nCode=" + err.code);
}

function getMedication() {
    dbShell.transaction(
        function(tx) {
            tx.executeSql(
                "SELECT id, name, type, amount, unit FROM medication ORDER BY name ASC",
                [],
                function (tx,results) {
                    medication = results;
                    renderMedication();
                },
                errorHandler
            );
        },
        errorHandler
    );
}

function getMedicationReminders(medication_id) {
    dbShell.transaction(
        function(tx) {
            tx.executeSql(
                "SELECT id, medication_id, time FROM medication_reminder WHERE medication_id = " + medication_id + " ORDER BY time ASC",
                [],
                function (tx,results) {
                    medicationReminders = results;
                    //renderMedicationDoses();
                    // Leaving this commented out, as I'm not sure I need to render Doses in the same way
                },
                errorHandler
            );
        },
        errorHandler
    );
}

function renderMedication() {
    $('.db-medication').append(
        '<h2>Reliever Medication</h2>',
        '<table class="table btn-table medication-table reliever-medication-table"></table>',
        '<h2>Preventer Medication</h2>',
        '<table class="table btn-table medication-table preventer-medication-table"></table>',
        '<h2>Other Medication</h2>' ,
        '<table class="table btn-table medication-table other-medication-table"></table>',
    );
    $('medication-table').append(
        '<thead>' +
            '<tr>' +
                '<th scope="col">Name</th>' +
                '<th scope="col">Dose Size</th>' +
                '<th scope="col">Edit</th>' +
            '</tr>' +
        '</thead>',
        '<tbody></tbody>'
    );
    for (var i = 0, len = medication.length; i < len; i++) {
        console.log(medication.length);
        $( '.' + medication[i].type + '-medication-table tbody').append(
            '<tr>' +
                '<th scope="row">' + medication[i].name + '</th>' +
                '<th scope="row">' + medication[i].amount + medication[i].unit + '</th>' +
                '<th><a href="edit.html?id="' + medication[i].id + '" class="btn btn-primary">Edit <span class="entypo-pencil"></span></th>' +
            '</tr>'
        );
    }
}
