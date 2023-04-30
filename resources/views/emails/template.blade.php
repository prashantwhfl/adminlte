<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
        <title>{{ Config::get('Site.title') }}</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                -webkit-text-size-adjust: none;
                -ms-text-size-adjust: none;
                font-family: 'Poppins', sans-serif;
                font-weight: 400;
            }
            html {
                width: 100%;
            }

            p {
                font-weight: 400;
            }

            td {
                color: #000000;
            }

            table {
                border-spacing: 0;
                border-collapse: collapse;
            }

            img {
                display: block !important;
            }

            table td {
                border-collapse: collapse;
            }
        </style>
    </head>
    <body marginwidth="0" marginheight="0" style="margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; background: #f4f4f4;" offset="0" topmargin="0" leftmargin="0">
        <table border="0" bgcolor="#36373a" align="center" width="100%" cellspacing="0" cellpadding="0" style="max-width: 600px; margin:80px auto; overflow: hidden; box-shadow: 0 0 16px rgba(0, 0, 0, 0.1); background-color: #fff; border-radius: 6px;">
            <tbody>
                <tr>
                    <td topmargin="0" leftmargin="0" cellspacing="0" cellpadding="0" marginwidth="0" marginheight="0">
                        <table border="0" align="center" width="100%" cellspacing="0" cellpadding="0" class="">
                            <tbody>
                                <tr>
                                    <td align="center" valign="top">
                                        <table class="main" border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td style="-moz-border-radius: 4px 4px 0px 0px; border-radius: 4px 4px 0px 0px;"
                                                    bgcolor="#FFFFFF" align="center" valign="top">
                                                        <table class="two-left-inner" border="0" align="center" width="100%" cellspacing="0" cellpadding="0" style="background: #f7f7ff;">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="center" valign="top" height="25"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center" valign="top" style="padding: 0 10px 5px;">
                                                                        <table border="0" align="center" width="100" cellspacing="0" cellpadding="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center" valign="top">
                                                                                        <a href="{{WEBSITE_URL}}"><img editable="true" mc:edit="km-01" src="{{WEBSITE_IMG_URL.'email-logo.png'}}" alt="{{ Config::get('Site.title') }} - Logo" style="max-width: 150px"></a>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center" valign="top" height="25"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php echo $messageBody; ?>

                        <table border="0" align="center" cellspacing="0" cellpadding="0" style="background-color: #ebebeb;">
                            <tbody>
                                <tr>
                                    <td align="center" valign="top" style="font-weight: 500;font-size:12px;line-height:20px;color: #000000;padding: 20px 15px 10px;">
                                        We're committed to your privacy. Krunch uses the information you provide to us to contact you about our relevant content, products, and services. You may unsubscribe from these communications at any time. For more information, check out our
                                        <a href="{{route('user.cmsPage','privacy-policy')}}" style="text-decoration: none; color:#467ce5;"> Privacy Policy. </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="font-weight: 500;font-size:12px;line-height:20px;padding: 0 15px 20px;text-align: center;">
                                        By creating a krunch account, you're agreeing to accept the krunck
                                        <a href="{{route('user.cmsPage','terms-conditions')}}" style="text-decoration: none; color:#467ce5;">Terms of Service</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>