 <!DOCTYPE html>
  <html>
    <head>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

    <body>

        <!-- let's start the main -->
        <div class="container" id="cont">
          <h1 class="header center black-text">Yalla negeb tweets</h1>


          <div class="row center">
            <form class="col s12" id="form" action="fetch.php" method="post">
              <div class="row">
                <div class="input-field col s9">
                  <input placeholder="The name of the page or twitter account" name="user" type="text">
                  <input type="hidden" value="" name="fetched_data">
                </div>

                 <div class="input-field col s3">
                    <button class="btn waves-effect waves-light" type="submit">dawar
                      <i class="material-icons right">send</i>
                    </button>
                 </div>

               </div>

              <div class="row loading" style="display: none">
                <div class="center col s11">
                  <div class="preloader-wrapper big active">
                    <div class="spinner-layer spinner-green-only">
                      <div class="circle-clipper left">
                        <div class="circle"></div>
                      </div><div class="gap-patch">
                        <div class="circle"></div>
                      </div><div class="circle-clipper right">
                        <div class="circle"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>


          <div class="input-field col center 12 export-button-container" style="display: none;">
              <button class="btn btn-lg waves-effect waves-light" name="export">Export
                <i class="material-icons right">send</i>
              </button>
           </div>
          </div>


        </div>
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>

      <script type="text/javascript">
        jQuery('document').ready(function($) {
           $('#cont').on('submit', '#form', function(e) {
            e.preventDefault();
            if($('input[name=user]').val() != '') {
               $('.loading').show();
               $('input[name=fetched_data]').val('');
                // start to send data
                 $.ajax({
                    type: "POST",
                    url: 'fetch.php',
                    data: { user: $('input[name=user]').val() },
                    success: function(data) {
                      $('input[name=fetched_data]').val(data);
                      $('.loading').hide();
                       if($('input[name=fetched_data]').val() != '') {
                          // show the export button
                          $('.export-button-container').show();
                       }
                    },
                    error: function(data) {
                      var message = JSON.parse(data.responseText);
                      Materialize.toast(message.message, 4000) // 4000 is the duration of the toast
                      $('.loading').hide();
                    }
                });
            }
          });


           // export the data on click
         $('button[name=export]').on('click', function() {
            var value = $('input[name=fetched_data]').val();
            $.ajax({
              type: "POST",
              url: 'export.php',
              data: { content: value },
              success: function(data) {
                  // if (data == 'success') {
                    console.log('asdads');
                    document.location = 'export.csv'
                  // }
              }
              });
            });
          });

      </script>
    </body>
  </html>
