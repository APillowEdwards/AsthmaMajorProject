(function(){
  var base_url = 'http://localhost/Asthma/rest/web/'

  $('.app').find('.sync-button').click(function () {
      $('.app').find('.sync-button').append('<img class="sync-indicator" src="../../img/ajax-loader.gif" />');
      $.ajax({
          url: base_url + 'medication',
          dataType: "json",
          method: "GET",
          success: function (data, textStatus, jqXHR) {
              var online_medications = data;
              console.log(online_medications);
              if ( !medications ) {
                  loadMedications();
              }
              // Records are differentiated using the "created_at" value
              // for each local medication
              for (var local_id = 0, len_local = medications.length; local_id < len_local; local_id++) {
                  if ( medications[local_id].db_id < 0 ) {
                      // Medication has not yet been saved to the online DB, so needs creating
                      ajaxPostMedication(medications[local_id], local_id);
                  } else {
                      // Medication has been saved to the online DB, so needs merging
                      for (var online_i = 0, len_online = online_medications.length; online_i < len_online; online_i++) {
                          if ( medications[local_id].db_id == online_medications[online_i].id ) {
                              // Online takes precedence by 10 seconds, to avoid rounding errors with the comparison
                              if ( medications[local_id].updated_at - 10000 <= new Date(online_medications[online_i].updated_at).getTime() ) {
                                  // Online takes precendence, save online version
                                  var online_medication = online_medications[online_i];
                                  online_medication['db_id'] = online_medication['id'];
                                  delete online_medication['id'];
                                  delete online_medication['user_id'];
                                  online_medication['created_at'] = new Date(online_medications[online_i].updated_at).getTime();
                                  online_medication['updated_at'] = new Date(online_medications[online_i].updated_at).getTime();

                                  saveMedication(online_medication, local_id, false);
                              } else {
                                  // Local takes precendence
                                  ajaxPatchMedication(medications[local_id], local_id);
                              }
                          }
                      }
                  }
              }
          },
          error: function (jqXHR, textStatus, errorThrown) {
          }
      });
      $('.sync-indicator').remove();
  });

  function ajaxPostMedication(medication, id) {
      $.ajax({
          url: base_url + 'medication',
          dataType: "json",
          data: $.extend({}, medication, {'user_id': 1}), // This adds everything to "Andy", for now
          method: "POST",
          success: function (data, textStatus, jqXHR) {
              medication.db_id = data.id;
              saveMedication(medication, id); // Save itself to localStorage
          },
          error: function (jqXHR, textStatus, errorThrown) {
              $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
              $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + '</p>');
          }
      });
  }

  /* PATCH request, to update the medication with local id 'id' */
  function ajaxPatchMedication(medication, id) {
      $.ajax({
          url: base_url + 'medication/' + medications[id].db_id,
          dataType: "json",
          data: medication, // This adds everything to "Andy", for now
          method: "PATCH",
          success: function (data, textStatus, jqXHR) {
              medication.db_id = data.id;
              saveMedication(medication, id); // Save itself to localStorage
          },
          error: function (jqXHR, textStatus, errorThrown) {
              $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
              $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + ': http://www.andrewedwards.co.uk/asthma/rest/web/medications?id=' + id + '</p>');
          }
      });
  }
})();
