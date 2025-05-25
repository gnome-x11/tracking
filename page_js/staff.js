


      function alert_toast(message, type = 'success') {
          const toast = $(`<div class="alert-toast ${type}">${message}</div>`);
          $('body').append(toast);
          setTimeout(() => toast.remove(), 3000);
      }

      // Add this before the visitor button handler
      function uni_modal(title, url, size = "") {
          $('#uni_modal').remove();
          $.ajax({
              url: url,
              error: err => {
                  console.log(err);
                  alert_toast("An error occurred", 'danger');
              },
              success: function (resp) {
                  $('#uni_modal').remove();
                  $('body').append(`
              <div class="modal fade" id="uni_modal" role="dialog">
                  <div class="modal-dialog ${size}" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title">${title}</h5>
                          </div>
                          <div class="modal-body">${resp}</div>
                      </div>
                  </div>
              </div>
          `);
                  $('#uni_modal').modal('show');
              }
          });
      }

      // JavaScript to handle image loading and sizing
      document.addEventListener('DOMContentLoaded', function () {
          const studentPhoto = document.getElementById('studentPhoto');

          function adjustImage() {
              if (!studentPhoto.complete) return;

              const container = studentPhoto.closest('.photo-container-wrapper');
              const containerWidth = container.offsetWidth;
              const containerHeight = container.offsetHeight;

              if (studentPhoto.naturalWidth > studentPhoto.naturalHeight) {
                  // Landscape image
                  studentPhoto.style.width = '90%';
                  studentPhoto.style.height = 'auto';
              } else {
                  // Portrait or square image
                  studentPhoto.style.height = '90%';
                  studentPhoto.style.width = 'auto';
              }
          }

          // Run on load and if image loads later
          studentPhoto.onload = adjustImage;
          if (studentPhoto.complete) adjustImage();
      });

      $('#manage-records').submit(function (e) {
          e.preventDefault();
          $.ajax({
              url: 'ajax.php?action=save_track',
              data: new FormData($(this)[0]),
              cache: false,
              contentType: false,
              processData: false,
              method: 'POST',
              success: function (resp) {
                  resp = JSON.parse(resp);
                  if (resp.status == 1) {
                      const studentId = $('#student_id').val().trim();

                      alert_toast("Data successfully saved", 'success');
                      setTimeout(() => {
                          window.location.reload();
                      }, 500); // 1.5 seconds delay to see the toast
                  }
              }
          });
      });


      $('#manage_visitor').click(function () {
          uni_modal("Visitor Entry", "manage_visitor.php", "mid-large")

      })

      $(document).ready(function () {

          $('.delete_person').click(function () {
              var id = $(this).data('id');
              delete_logs(id);
          });
      });

      let inactivityTimer;
      const overlay = document.getElementById('splashOverlay');
      const localVideo = document.getElementById('localVideo');

      function resetTimer() {
          clearTimeout(inactivityTimer);
          hideSplash();
          inactivityTimer = setTimeout(showSplash, 60000); // 30 sec
      }

      function showSplash() {
          overlay.style.display = 'flex';
          localVideo.currentTime = 0; // Reset to start
          localVideo.play().catch(error => {
              console.log('Video play prevented:', error);
          });
      }

      function hideSplash() {
          overlay.style.display = 'none';
          localVideo.pause();
      }

      // Event listeners for user activity
      const events = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'];
      events.forEach(event => {
          document.addEventListener(event, resetTimer);
      });

      // Initial setup
      resetTimer();

      let timeout = null;

      function checkIDAuto() {
          clearTimeout(timeout);
          timeout = setTimeout(() => {
              const scannedValue = $('#student_id').val().trim();
              if (scannedValue !== "") {
                  // First: Try matching student ID
                  $.ajax({
                      url: 'ajax.php?action=get_pdetails',
                      method: "POST",
                      data: { student_id: scannedValue },
                      success: function (resp) {
                          try {
                              const json = JSON.parse(resp);

                              if (json.status === 1) {
                                  // Valid student, show modal and submit form
                                  $('#studentName').text(json.name);
                                  $('#studentCollege').text(json.college);
                                  $('#studentCourse').text(json.course);
                                  $('#studentYear').text(json.year_level);
                                  $('#studentStanding').text(json.standing);
                                  if (json.photo) {
                                      $('#studentPhoto').attr('src', json.photo);
                                  }

                                  $('#studentModal').modal('show');
                                  $('[name="person_id"]').val(json.id);
                                  $('#details').show();

                                  setTimeout(() => {
                                      $('#manage-records').submit();
                                  }, 1500);

                              } else {
                                  // If not found as student, fallback to visitor scan
                                  scanVisitorToken(scannedValue);
                              }
                          } catch (e) {
                              console.error("Student response parse error:", e);
                              scanVisitorToken(scannedValue);
                          }
                      },
                      error: function () {
                          console.error("Student AJAX error. Trying visitor...");
                          scanVisitorToken(scannedValue);
                      }
                  });
              } else {
                  reset_form();
              }
          }, 2000);
      }

      function scanVisitorToken(token) {

          sessionStorage.setItem('last_visitor_token', token);

          $.ajax({
              url: 'ajax.php?action=scan_visitor',
              method: "POST",
              data: {
                  token: token,
                  establishment_id: establishmentId
              },
              success: function (resp) {
                  try {
                      const result = JSON.parse(resp);
                      if (result.status === "success") {
                          let actionText = "";
                          let modal;

                          if (result.action === 'time_in') {
                              actionText = "Visitor Time-in Recorded";
                              $('#timeInEstablishmentName').text(establishmentName);
                              modal = new bootstrap.Modal(document.getElementById('visitorTimeInModal'));
                          } else if (result.action === 'time_out') {
                              actionText = "Visitor Time-out Recorded";
                              $('#timeOutEstablishmentName').text(establishmentName);
                              modal = new bootstrap.Modal(document.getElementById('visitorTimeOutModal'));
                          }

                          modal.show();

                          // Auto close modal after 3 seconds, then show toast
                          setTimeout(() => {
                              modal.hide();
                              alert_toast(actionText, 'success');
                          }, 2000);

                      } else {
                          alert_toast(result.message || "Invalid visitor token", 'danger');
                      }
                  } catch (e) {
                      console.error("Visitor response parse error:", e);
                      alert_toast("Unexpected error while scanning visitor QR", 'danger');
                  }
              },
              error: function () {
                  alert_toast("Failed to connect. Please try again.", 'danger');
              }
          });

          // Reset scanner input after visitor attempt
          // Reset scanner input and refocus after visitor attempt
          setTimeout(() => {
              const input = $('#student_id');
              input.val('');
              input.focus();
          }, 2000);

      }

      document.addEventListener('DOMContentLoaded', function () {
          const sidebar = document.getElementById('sidebar');
          const overlay = document.querySelector('.overlay');

          if (sidebar) {
              sidebar.classList.remove('active');
          }

          if (overlay) {
              overlay.style.display = 'none';
          }
      });

      $('#saveVisitor').click(function (e) {
          e.preventDefault();
          var formData = new FormData($('#visitorForm')[0]);

          $.ajax({
              url: 'ajax.php?action=save_visitor',
              method: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              success: function (resp) {
                  resp = JSON.parse(resp);
                  if (resp.status == 1) {
                      alert_toast("Visitor saved successfully", 'success');
                      $('#visitorModal').modal('hide');
                      $('#visitorForm')[0].reset();
                  } else {
                      alert_toast("Error saving visitor", 'danger');
                  }
              }
          });
      });



      function get_person() {
          start_load()
          $.ajax({
              url: 'ajax.php?action=get_pdetails',
              method: "POST",
              data: { student_id: $('#student_id').val() },
              success: function (resp) {
                  if (resp) {
                      resp = JSON.parse(resp)
                      if (resp.status == 1) {
                          // Update student card
                          $('#studentName').text(resp.name)
                          $('#studentCollege').text(resp.college)
                          $('#studentCourse').text(resp.course)
                          $('#studentYear').text(resp.year_level)
                          $('#studentStanding').text(resp.standing)
                          // Set photo if available
                          if (resp.photo) {
                              $('#studentPhoto').attr('src', resp.photo)
                          } else {
                              $('#studentPhoto').attr('src', 'path/to/default/photo.jpg') //dagdagan mo nalang to ng default photo para may fallback ka incase mag error path ng image
                          }

                          // Show the student card
                          $('#studentCard').fadeIn()

                          // Update form fields
                          $('[name="person_id"]').val(resp.id)
                          $('#details').show()
                          end_load()


                      }
                  }
              }
          })
      }

      function reset_form() {
          $('#student_id').val('')
          $('#details').hide()
          $('#studentCard').hide()
      }



      $(document).ready(function () {
          // Get stored student ID
          const lastStudentId = sessionStorage.getItem('last_student_id');

          if (lastStudentId) {
              $('#student_id')
                  .val(lastStudentId)
                  .focus()
                  .select();
              // Clear the stored value
              sessionStorage.removeItem('last_student_id');
          } else {
              // Default focus if no stored value
              $('#student_id').focus();
          }
      });

      $(document).ready(function () {
          // Get stored visitor token
          const lastVisitorToken = sessionStorage.getItem('last_visitor_token');

          if (lastVisitorToken) {
              $('#visitor_token')
                  .val(lastVisitorToken)
                  .focus()
                  .select();
              // Clear the stored value after restoring
              sessionStorage.removeItem('last_visitor_token');
          } else {
              // Default focus if no stored value
              $('#visitor_token').focus();
          }
      });
