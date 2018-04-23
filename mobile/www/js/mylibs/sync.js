(function(){
  $('.app').find('.sync-button').click(function () {
      $('.app').find('.sync-button').append('<img class="sync-indicator" src="../../img/ajax-loader.gif" />');
      $.ajax({
          url: base_url + 'medication',
          dataType: "json",
          method: "GET",
          headers: {
              "Authorization": "Basic " + localStorage.getItem( "credentials" )
          },
          success: function (data, textStatus, jqXHR) {
              console.log("Success!");
              var meds_by_dbid = [];
              var new_meds = []
              var online_meds_by_id = [];

              loadMedications();

              // Populate meds_by_dbid and new_meds with medications, where the index is equal to db_id for m_b_di
              for (var i = 0, len = medications.length; i < len; i++) {
                  if ( medications[i].db_id > 0 ) {
                      meds_by_dbid[ medications[i].db_id ] = Object.assign({}, medications[i], {'local_id': i});
                  } else {
                      new_meds.push( Object.assign({}, medications[i], {'local_id': i}) );
                  }
              }
              // Populate online_meds_by_id, where the index is equal to its id in the database
              var largest_db_id = -1;
              for (var i = 0, len = data.length; i < len; i++) {
                  online_meds_by_id[ data[i].id ] = data[i];
                  largest_db_id = ( data[i].id > largest_db_id ? data[i].id : largest_db_id );
              }

              // Update shared records, and save new online ones
              // This currently intends to leave orphaned local versions, not pushing them
              // Should they be deleted?
              for (var i = 1, len = largest_db_id; i <= len; i++) {
                  if ( typeof online_meds_by_id[i] !== 'undefined' ) {
                      if ( typeof meds_by_dbid[i] !== 'undefined' ) {
                          // Make a comparison
                          // Online takes precedence by 10 seconds, to avoid rounding errors with the comparison
                          if ( meds_by_dbid[i].updated_at - 10000 <= new Date(online_meds_by_id[i].updated_at).getTime() ) {
                              // Online takes precendence, save online version
                              online_meds_by_id[i]['db_id'] = online_meds_by_id[i]['id'];
                              delete online_meds_by_id[i]['id'];
                              delete online_meds_by_id[i]['user_id'];
                              online_meds_by_id[i]['created_at'] = new Date(online_meds_by_id[i].updated_at).getTime();
                              online_meds_by_id[i]['updated_at'] = new Date(online_meds_by_id[i].updated_at).getTime();

                              saveMedication(online_meds_by_id[i], meds_by_dbid[i].local_id, false);
                          } else {
                              // Local takes precendence
                              ajaxPatchMedication(meds_by_dbid[i], meds_by_dbid[ meds_by_dbid[i].local_id ]);
                          }
                      } else {
                          // Save new online medications
                          online_meds_by_id[i]['db_id'] = online_meds_by_id[i]['id'];
                          delete online_meds_by_id[i]['id'];
                          delete online_meds_by_id[i]['user_id'];
                          online_meds_by_id[i]['created_at'] = new Date(online_meds_by_id[i].updated_at).getTime();
                          online_meds_by_id[i]['updated_at'] = new Date(online_meds_by_id[i].updated_at).getTime();

                          saveMedication(online_meds_by_id[i], -1, false);
                      }
                  }
              }

              // Push new local records
              for (var i = 0, len = new_meds.length; i < len; i++) {
                  ajaxPostMedication( new_meds[i], new_meds[i].local_id );
              }
              // Push new Doses
              loadDoses();

              for (var i = 0, len = doses.length; i < len; i++) {
                  let this_dose = doses[i];
                  let this_dose_index = i;
                  if ( this_dose.new ) {
                      var date = new Date(this_dose.taken_at);
                      // Needs fixing yo
                      var formatted_date = (date.getDate() < 10 ? "0" : "") + date.getDate() + "/";
                      formatted_date += (date.getMonth() + 1 < 10 ? "0" : "") + (date.getMonth() + 1) + "/";
                      formatted_date += date.getFullYear() + " ";
                      formatted_date += (date.getHours() < 10 ? "0" : "") + date.getHours() + ":";
                      formatted_date += (date.getMinutes() < 10 ? "0" : "") + date.getMinutes() + ":";
                      formatted_date += (date.getSeconds() < 10 ? "0" : "") + date.getSeconds();
                      $.ajax({
                          url: base_url + 'dose',
                          dataType: "json",
                          headers: {
                              "Authorization": "Basic " + localStorage.getItem( "credentials" )
                          },
                          data: {
                              medication_id: medications[ this_dose.medication_id ].db_id,
                              dose_size: this_dose.dose_size,
                              // d/m/Y H:i:s
                              taken_at: formatted_date,
                          },
                          method: "POST",
                          success: function (data, textStatus, jqXHR) {
                              this_dose.new = false;
                              saveDose(this_dose, this_dose_index);
                          },
                          error: function (jqXHR, textStatus, errorThrown) {
                              $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
                              $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + '</p>');
                          }
                      });
                  }
              }

              for (var i = 0, len = doses.length; i < len; i++) {
                  let this_dose = doses[i];
                  let this_dose_index = i;
                  if ( this_dose.new ) {
                      var date = new Date(this_dose.taken_at);

                      var formatted_date = (date.getDate() < 10 ? "0" : "") + date.getDate() + "/";
                      formatted_date += (date.getMonth() + 1 < 10 ? "0" : "") + (date.getMonth() + 1) + "/";
                      formatted_date += date.getFullYear() + " ";
                      formatted_date += (date.getHours() < 10 ? "0" : "") + date.getHours() + ":";
                      formatted_date += (date.getMinutes() < 10 ? "0" : "") + date.getMinutes() + ":";
                      formatted_date += (date.getSeconds() < 10 ? "0" : "") + date.getSeconds();
                      $.ajax({
                          url: base_url + 'dose',
                          dataType: "json",
                          headers: {
                              "Authorization": "Basic " + localStorage.getItem( "credentials" )
                          },
                          data: {
                              medication_id: medications[ this_dose.medication_id ].db_id,
                              dose_size: this_dose.dose_size,
                              // d/m/Y H:i:s
                              taken_at: formatted_date,
                          },
                          method: "POST",
                          success: function (data, textStatus, jqXHR) {
                              this_dose.new = false;
                              saveDose(this_dose, this_dose_index);
                          },
                          error: function (jqXHR, textStatus, errorThrown) {
                              $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
                              $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + '</p>');
                          }
                      });
                  }
              }
          },
          error: function (jqXHR, textStatus, errorThrown) {
              console.log("Failed!");
              console.log(errorThrown + ": " + textStatus);
          }
      });

      $.ajax({
          url: base_url + 'trigger',
          dataType: "json",
          method: "GET",
          headers: {
              "Authorization": "Basic " + localStorage.getItem( "credentials" )
          },
          success: function (data, textStatus, jqXHR) {
              loadTriggers();
              for (var i = 0, len = data.length; i < len; i++) {
                  if ( !triggers.includes( data[i].name ) ) {
                      triggers.push( data[i].name );
                  }
              }

              var online_trigger_names = [];
              for (var i = 0, len = data.length; i < len; i++) {
                  online_trigger_names.push( data[i].name );
              }
              for (var i = 0, len = triggers.length; i < len; i++) {
                  if ( !online_trigger_names.includes( triggers[i] ) ) {
                      $.ajax({
                          url: base_url + 'trigger',
                          dataType: "json",
                          data: {'name': triggers[i]},
                          method: "POST",
                          headers: {
                              "Authorization": "Basic " + localStorage.getItem( "credentials" )
                          },
                          success: function (data, textStatus, jqXHR) {},
                          error: function (jqXHR, textStatus, errorThrown) {
                              $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
                              $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + '</p>');
                          }
                      });
                  }
              }
          },
          error: function (jqXHR, textStatus, errorThrown) {
              console.log("Failed!");
              console.log(errorThrown + ": " + textStatus);
          }
      });

      loadExacerbations();

      for (var i = 0, len = exacerbations.length; i < len; i++) {
          let this_exacerbation = exacerbations[i];
          let this_exacerbation_index = i;
          if ( this_exacerbation.new ) {
              var date = new Date(this_exacerbation.happened_at);

              var formatted_date = (date.getDate() < 10 ? "0" : "") + date.getDate() + "/";
              formatted_date += (date.getMonth() + 1 < 10 ? "0" : "") + (date.getMonth() + 1) + "/";
              formatted_date += date.getFullYear() + " ";
              formatted_date += (date.getHours() < 10 ? "0" : "") + date.getHours() + ":";
              formatted_date += (date.getMinutes() < 10 ? "0" : "") + date.getMinutes() + ":";
              formatted_date += (date.getSeconds() < 10 ? "0" : "") + date.getSeconds();

              $.ajax({
                  url: base_url + 'exacerbation',
                  dataType: "json",
                  headers: {
                      "Authorization": "Basic " + localStorage.getItem( "credentials" )
                  },
                  data: {
                      happened_at: formatted_date,
                  },
                  method: "POST",
                  success: function (data, textStatus, jqXHR) {
                      var new_exacerbation = data;
                      // push triggers
                      $.ajax({
                          url: base_url + 'trigger',
                          dataType: "json",
                          method: "GET",
                          headers: {
                              "Authorization": "Basic " + localStorage.getItem( "credentials" )
                          },
                          success: function (data, textStatus, jqXHR) {
                              var new_exacerbation_id = new_exacerbation.id;

                              var trigger_ids = [];
                              console.log(this_exacerbation);
                              console.log(data);
                              for (var j = 0, jlen = this_exacerbation.triggers.length; j < jlen; j++) {
                                  for (var k = 0, klen = data.length; k < klen; k++) {
                                      if ( this_exacerbation.triggers[j] == data[k].name ) {
                                          trigger_ids.push( data[k].id );
                                      }
                                  }
                              }

                              for (var j = 0, jlen = trigger_ids.length; j < jlen; j++) {
                                  $.ajax({
                                      url: base_url + 'exacerbation-trigger',
                                      dataType: "json",
                                      data: {'exacerbation_id': new_exacerbation_id, 'trigger_id': trigger_ids[j]},
                                      method: "POST",
                                      headers: {
                                          "Authorization": "Basic " + localStorage.getItem( "credentials" )
                                      },
                                      success: function (data, textStatus, jqXHR) {},
                                      error: function (jqXHR, textStatus, errorThrown) {
                                          $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
                                          $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + '</p>');
                                      }
                                  });
                              }
                          },
                          error: function (jqXHR, textStatus, errorThrown) {
                              $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
                              $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + '</p>');
                          }
                      });

                      // push symptoms
                      $.ajax({
                          url: base_url + 'symptom',
                          dataType: "json",
                          method: "GET",
                          headers: {
                              "Authorization": "Basic " + localStorage.getItem( "credentials" )
                          },
                          success: function (data, textStatus, jqXHR) {
                              var new_exacerbation_id = new_exacerbation.id;

                              for (var j = 0, jlen = this_exacerbation.symptoms.length; j < jlen; j++) {
                                  $.ajax({
                                      url: base_url + 'symptom',
                                      dataType: "json",
                                      data: {'exacerbation_id': new_exacerbation_id, 'name': this_exacerbation.symptoms[j].name, 'severity': this_exacerbation.symptoms[j].severity},
                                      method: "POST",
                                      headers: {
                                          "Authorization": "Basic " + localStorage.getItem( "credentials" )
                                      },
                                      success: function (data, textStatus, jqXHR) {},
                                      error: function (jqXHR, textStatus, errorThrown) {
                                          $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
                                          $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + '</p>');
                                      }
                                  });
                              }
                          },
                          error: function (jqXHR, textStatus, errorThrown) {
                              $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
                              $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + '</p>');
                          }
                      });

                      exacerbations[this_exacerbation_index].new = false;
                      storage.setItem( 'exacerbations', JSON.stringify(exacerbations) );
                  },
                  error: function (jqXHR, textStatus, errorThrown) {
                      $('.app').find('.sync-button').append('<span class="entypo-thumbs-down"></span>');
                      $('.app').find('.sync-button').after('<p class="sync-error">' + errorThrown + '</p>');
                  }
              });

          }
      }

      $('.sync-indicator').remove();
  });

  /* POST request, to push the medication with local id 'id' */
  function ajaxPostMedication(medication, id) {
      $.ajax({
          url: base_url + 'medication',
          dataType: "json",
          data: $.extend({}, medication), // This adds everything to "Andy", for now
          method: "POST",
          headers: {
              "Authorization": "Basic " + localStorage.getItem( "credentials" )
          },
          success: function (data, textStatus, jqXHR) {
              medication.db_id = data.id;
              delete medication['local_id'];
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
          url: base_url + 'medication/' + medication.db_id,
          dataType: "json",
          data: medication, // This adds everything to "Andy", for now
          method: "PATCH",
          headers: {
              "Authorization": "Basic " + localStorage.getItem( "credentials" )
          },
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
