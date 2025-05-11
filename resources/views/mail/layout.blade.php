<!doctype html>
<html lang="pt-BR">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>@yield('title') | Grupo Saber</title>
  </head>
  <body style="background-color: #009AE6; margin: 0; padding: 0; font-family: Helvetica, sans-serif; -webkit-font-smoothing: antialiased; font-size: 16px; line-height: 1.3; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
      <tr>
        <td>&nbsp;</td>
        <td style="margin: 0 auto !important; max-width: 600px; padding: 0; padding-top: 24px; width: 600px;">
          <div style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 600px; padding: 0;">

            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="background: #ffffff; width: 100%;">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td style="padding: 24px 44px; box-sizing: border-box;">
                    <div style="border-bottom: #eaebed 1px solid; padding-bottom: 6px; margin-bottom: 20px; text-align: center;">
                        @php
                            $imageUrl = public_path('img/Logo.png');
                            $message->embed($imageUrl, 'Logo')
                        @endphp
                        <img src="{{ $message->embed($imageUrl) }}" alt="" style="width: 150px">
                    </div>
                  @yield('content')
                </td>
              </tr>
              <tr style="clear: both; padding-top: 24px; text-align: center; width: 100%; position: relative;">
                <td style="padding: 0;">
                  <hr style="border: 0; border-top: 1px solid #eaebed;">
                  <span style="display: inline-block;margin-top: 20px;">Atenciosamente</span>
                  <br>
                  <span style="color: #9a9ea6; font-size: 16px; text-align: center; display: inline-block; margin-bottom: 20px;">- Grupo Saber.</span>
                </td>
              </tr>

              <!-- END MAIN CONTENT AREA -->
            </table>
          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>
